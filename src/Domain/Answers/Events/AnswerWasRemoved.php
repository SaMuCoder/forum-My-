<?php

namespace App\Domain\Answers\Events;

use App\Domain\Answers\Answer\AnswerId;
use App\Domain\AbstractEvent;
use App\Domain\DomainEvent;
use JsonSerializable;

class AnswerWasRemoved extends AbstractEvent implements DomainEvent, JsonSerializable
{
    /**
     * Creates a AnswerWasRemoved
     *
     * @param AnswerId $answerId
     */
    public function __construct(private readonly AnswerId $answerId)
    {
        parent::__construct();
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
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return  [
            'answerId' => $this->answerId
        ];
    }

}

