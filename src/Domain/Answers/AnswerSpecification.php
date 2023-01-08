<?php

/** This is a par of forum... */

declare(strict_types=1);

namespace App\Domain\Answers;

/**
 * AnswerSpecification
 *
 * @package App\Domain\Answers
 */
interface AnswerSpecification
{

        /**
        * This specification is satisfied by provided answer
        *
        * @param Answer $answer
        * @return bool
        */
        public function isSatisfiedBy(Answer $answer): bool;
}