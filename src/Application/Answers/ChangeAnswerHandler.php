<?php

namespace App\Application\Answers;

use App\Application\Command;
use App\Application\CommandHandler;
use App\Application\CommandHandlerMethods;
use App\Domain\Exception\SpecificationFails;
use App\Domain\Answers\Answer;
use App\Domain\Answers\AnswerRepository;
use App\Domain\Answers\Specification\OwnedByRequester;
use Psr\EventDispatcher\EventDispatcherInterface;

class ChangeAnswerHandler implements CommandHandler
{
    use CommandHandlerMethods;

    public function __construct(
        private readonly AnswerRepository $answers,
        private readonly OwnedByRequester $ownedByRequester,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @inheritDoc
     * @param ChangeAnswerCommand $command
     */
    public function handle(Command $command): Answer
    {
        $answer = $this->answers->withAnswerId($command->answerId());

        if (!$this->ownedByRequester->isSatisfiedBy($answer)) {
            throw new SpecificationFails(
                "Could not change selected answer. " .
                "Answers can only be changed by its owner."
            );
        }

        $this->dispatchEventsFrom(
            $answer->change($command->body()),
            $this->dispatcher
        );
        return $answer;
    }
}
