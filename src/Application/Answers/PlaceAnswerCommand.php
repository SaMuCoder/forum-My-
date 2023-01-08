<?php

namespace App\Application\Answers;

use App\Application\Command;
use App\Domain\Answers\Answer\AnswerId;
use App\Domain\Questions\Question\QuestionId;
use App\Domain\UserManagement\User\UserId;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\AsResourceObject;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\Attribute;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\RelationshipIdentifier;

/**
 * PlaceAnswerCommand
 *
 * @package App\Application\Answers
 */
#[AsResourceObject(type: "answers")]
class PlaceAnswerCommand implements Command
{
    /**
     * Creates a PlaceAnswerCommand
     *
     * @param UserId $owner
     * @param QuestionId $questionId
     * @param AnswerId $answerId
     * @param string $body
     */
    public function __construct(
        #[RelationshipIdentifier(type: "users")]
        private readonly UserId     $owner,
        #[RelationshipIdentifier(type: "questions")]
        private readonly QuestionId $questionId,
        #[RelationshipIdentifier(type: "answers")]
        private readonly AnswerId   $answerId,
        #[Attribute]
        private readonly string     $body
    ) {

    }

    /**
     * ownerUserId
     *
     * @return UserId
     */
    public function owner(): UserId
    {
        return $this->owner;
    }

    /**
     * questionId
     *
     * @return QuestionId
     */

    public function questionId(): QuestionId
    {
        return $this->questionId;
    }

    /**
     * answerId
     *
     * @return AnswerId
     */
    public function answerId(): AnswerId
    {
        return $this->answerId;
    }

    /**
     * body
     *
     * @return string
     */
    public function body(): string
    {
        return $this->body;
    }
}