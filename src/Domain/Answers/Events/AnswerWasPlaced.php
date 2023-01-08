<?php

namespace App\Domain\Answers\Events;

use App\Domain\AbstractEvent;
use App\Domain\Answers\Answer\AnswerId;
use App\Domain\UserManagement\User\UserId;
use JsonSerializable;
class AnswerWasPlaced extends AbstractEvent implements JsonSerializable
{
    /**
     * Creates a AnswerWasPlaced
     *
     * @param UserId $ownerUserId
     * @param AnswerId $answerId
     * @param string $body
     */
    public function __construct(
        private readonly UserId $ownerUserId,
        private readonly AnswerId $answerId,
        private readonly string $body
    ) {
        parent::__construct();
    }
    /**
     * ownerUserId
     *
     * @return UserId
     */
    public function ownerUserId(): UserId
    {
        return $this->ownerUserId;
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
    /**
     * jsonSerialize
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'ownerUserId' => $this->ownerUserId,
            'answerId' => $this->answerId,
            'body' => $this->body,
        ];
    }
}
