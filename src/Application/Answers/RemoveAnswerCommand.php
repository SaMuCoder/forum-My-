<?php

namespace App\Application\Answers;

use App\Application\Command;
use App\Domain\Answers\Answer\AnswerId;

class RemoveAnswerCommand implements Command
{

    public function __construct(private readonly AnswerId $answerId)
    {
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
}