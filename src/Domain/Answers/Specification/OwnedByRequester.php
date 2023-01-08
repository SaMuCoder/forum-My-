<?php

namespace App\Domain\Answers\Specification;

use App\Domain\Answers\Answer;
use App\Domain\Answers\AnswerSpecification;
use App\Domain\UserManagement\UserIdentifier;

class OwnedByRequester implements AnswerSpecification
{
    public function __construct(
        private readonly UserIdentifier $identifier
    ) {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(Answer $answer): bool
    {
        $loggedInUserId = $this->identifier->currentUser()->userId();
        return $loggedInUserId->equalsTo($answer->owner()->userId());
    }
}
