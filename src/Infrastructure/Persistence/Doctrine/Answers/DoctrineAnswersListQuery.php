<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Answers;

use App\Application\Answers\AnswersListQuery;
use App\Application\Answers\Models\AnswerEntry;
use App\Domain\UserManagement\User\UserId;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\AsResourceCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Traversable;

/**
 * DoctrineAnswersListQuery
 *
 * @package App\Infrastructure\Persistence\Doctrine\Answers
 */
#[AsResourceCollection(
    type: 'answers',
    metaFromMethod: "metaData",
    linksFromMethod: "paginationLinks"
)]
final class DoctrineAnswersListQuery extends AnswersListQuery
{

    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function executeCountQuery(): int
    {
        $query = $this->connection->createQueryBuilder()
            ->select("count(*) as total")
            ->from("answers", "a")
            ->leftJoin("a", "users", "u", "a.owner_id = u.id");

        $this->filterOwner($query);

        return (int) $query
            ->executeQuery()
            ->fetchOne();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function executeQuery(): Traversable
    {
        $query = $this->connection->createQueryBuilder()
            ->select("a.id as answerId, a.body, u.id as userId, u.name, u.email")
            ->from("answers", "a")
            ->leftJoin("a", "users", "u", "a.owner_id = u.id");

        $this->filterOwner($query);

        $data = $query
            ->setMaxResults($this->pagination()->rowsPerPage())
            ->setFirstResult($this->pagination()->offset())
            ->executeQuery()
            ->fetchAllAssociative();

        $answers = new ArrayCollection();

        foreach ($data as $entry) {
            $answers->add(new AnswerEntry($entry));
        }

        return $answers;
    }

    /**
     * @inheritDoc
     */
    protected function baseUrlPath(): string
    {
        return "/answers";
    }

    /**
     * @param QueryBuilder $query
     */
    private function filterOwner(QueryBuilder $query)
    {
        $param = $this->param(self::OWNER_FILTER, self::OWNER_ALL);
        switch ($param) {
            case self::OWNER_ALL;
                return;

            case self::OWNER_SELF;
                $userIdParam = $this->param(self::PARAM_USER_ID);
                break;

            case self::OWNER_OTHERS:
                $userIdParam = $this->param(self::PARAM_USER_ID);
                $query->where('u.id != :userId')
                    ->setParameter('userId', $userIdParam);
                return;


            default:
                $userIdParam = (string) new UserId($param);
        }


        $query->where('u.id = :userId')
            ->setParameter('userId', $userIdParam);
    }
}