<?php

namespace App\Application\Answers;

use App\Application\Command;
use App\Application\CommandHandler;
use App\Application\CommandHandlerMethods;
use App\Domain\Exception\SpecificationFails;
use App\Domain\Answers\Answer;
use App\Domain\Answers\AnswerRepository;
use App\Domain\Answers\Specification\IsActive;
use App\Domain\Answers\Specification\OwnedByRequester;
use Psr\EventDispatcher\EventDispatcherInterface;
class RemoveAnswerHandler implements CommandHandler
{
    use CommandHandlerMethods;

    public function __construct(
        private readonly AnswerRepository $answers,
        private readonly IsActive $isActive,
        private readonly OwnedByRequester $byRequester,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @inheritDoc
     * @param RemoveAnswerCommand $command
     */
    public function handle(Command $command): Answer
    {
        $answer = $this->answers->withAnswerId($command->answerId());

        if (!$this->byRequester->isSatisfiedBy($answer)) {
            throw new SpecificationFails(
                "Could not remove selected answer. " .
                "Answers can only be removed by its owner."
            );
        }

        if (!$this->isActive->isSatisfiedBy($answer)) {
            throw new SpecificationFails(
                "Could not remove selected answer. " .
                "Answer is closed or archive."
            );
        }

        $this->dispatchEventsFrom(
            $this->answers->remove($answer),
            $this->dispatcher
        );

        return $answer;
    }
}
