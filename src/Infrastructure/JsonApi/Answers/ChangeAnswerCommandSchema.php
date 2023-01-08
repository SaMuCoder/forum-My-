<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\Answers;

use App\Application\Answers\ChangeAnswerCommand;
use App\Domain\Answers\Answer\AnswerId;
use App\Domain\Questions\Question\QuestionId;
use Ramsey\Uuid\Uuid;
use Slick\JSONAPI\Object\AbstractResourceSchema;
use Slick\JSONAPI\Object\ResourceSchema;

/**
 * ChangeAnswerCommandSchema
 *
 * @package App\Infrastructure\JsonApi\Answers
 */
final class ChangeAnswerCommandSchema extends AbstractResourceSchema implements ResourceSchema
{
    /**
     * @inheritDoc
     */
    public function type($object): string
    {
        return 'answers';
    }

    /**
     * @inheritDoc
     */
    public function identifier($object): ?string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @inheritDoc
     */
    public function from($resourceObject): ChangeAnswerCommand
    {
        $questionId = new QuestionId($resourceObject->resourceIdentifier()->identifier());
        $answerId = new AnswerId($resourceObject->resourceIdentifier()->identifier());
        $attributes = (object) $resourceObject->attributes();
        return new ChangeAnswerCommand(
            $questionId,
            $answerId,
            $attributes->body ?: null
        );
    }

}