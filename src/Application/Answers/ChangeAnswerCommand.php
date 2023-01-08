<?php

namespace App\Application\Answers;

use App\Application\Command;
use App\Domain\Answers\Answer\AnswerId;
use App\Infrastructure\JsonApi\Answers\ChangeAnswerCommandSchema;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\AsResourceObject;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\Attribute;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\ResourceIdentifier;
use App\Domain\Questions\Question\QuestionId;
use App\Domain\UserManagement\User\UserId;

#[AsResourceObject(schemaClass: ChangeAnswerCommandSchema::class)]
class ChangeAnswerCommand implements Command
{
    public function __construct(
        private readonly AnswerId $answerId,
        private readonly ?string $body = null,
        private readonly ?QuestionId $questionId = null
    ) {
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
     * @return string|null
     */
    public function body(): ?string
    {
        return $this->body;
    }

    public function questionId(): QuestionId
    {
        return $this->questionId;
    }
}