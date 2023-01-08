<?php

namespace App\Domain\Answers\Answer;

use App\Domain\Exception\InvalidAggregateIdentifier;
use JsonSerializable;
use Ramsey\Uuid\Uuid;

class AnswerId implements \Stringable, JsonSerializable
{
    public function __construct(private ?string $answerIdStr = null)
    {
        $this->answerIdStr = $this->answerIdStr ?: Uuid::uuid4()->toString();

        if (!Uuid::isValid($this->answerIdStr)) {
            throw new InvalidAggregateIdentifier(
                "The provided answer identifier is not a valid UUID."
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->answerIdStr;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return $this->answerIdStr;
    }
}
