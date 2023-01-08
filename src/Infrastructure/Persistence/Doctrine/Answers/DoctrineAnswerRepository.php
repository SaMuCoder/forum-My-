<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Answers;

use App\Domain\Exception\EntityNotFound;
use App\Domain\Answers\Answer;
use App\Domain\Answers\Answer\AnswerId;
use App\Domain\Answers\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Answers\Events\AnswerWasRemoved;

/**
 * DoctrineAnswerRepository
 *
 * @package App\Infrastructure\Persistence\Doctrine\Answers
 */
final class DoctrineAnswerRepository implements AnswerRepository
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @inheritDoc
     */
    public function add(Answer $answer): Answer
    {
        $this->entityManager->persist($answer);
        return $answer;
    }

    /**
     * @inheritDoc
     */
    public function withAnswerId(AnswerId $answerId): Answer
    {
        $answer = $this->entityManager->find(Answer::class, $answerId);

        if ($answer instanceof Answer) {
            return $answer;
        }

        throw new EntityNotFound("There are no answers with ID '$answerId'");
    }

    /**
     * @inheritDoc
     */
    public function remove(Answer $answer): Answer
    {
        $this->entityManager->remove($answer);
        $answer->recordThat(new AnswerWasRemoved($answer->answerId()));
        return $answer;
    }
}