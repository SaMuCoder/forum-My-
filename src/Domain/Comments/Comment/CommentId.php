<?php

namespace App\Domain\Comments\Comment;

use App\Domain\Exception\InvalidAggregateIdentifier;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
class CommentId implements \Stringable, JsonSerializable
{
    public function __construct(private ?string $commentIdStr = null)
    {
        $this->commentIdStr = $this->commentIdStr ?: Uuid::uuid4()->toString();
        if (!Uuid::isValid($this->commentIdStr)) {
            throw new InvalidAggregateIdentifier(
                "The provided comment identifier is not a valid UUID."
            );
        }
    }
    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->commentIdStr;
    }
    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return $this->commentIdStr;
    }
}