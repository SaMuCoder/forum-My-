<?php

namespace spec\App\Application\Answers;

use App\Application\CommandHandler;
use App\Application\Answers\RemoveAnswerCommand;
use App\Application\Answers\RemoveAnswerHandler;
use App\Domain\Exception\SpecificationFails;
use App\Domain\Answers\Events\AnswerWasRemoved;
use App\Domain\Answers\Answer;
use App\Domain\Answers\AnswerRepository;
use App\Domain\Answers\Specification\IsActive;
use App\Domain\Answers\Specification\OwnedByRequester;
use PhpSpec\ObjectBehavior;
use Psr\EventDispatcher\EventDispatcherInterface;

class RemoveAnswerHandlerSpec extends ObjectBehavior
{

        private $answerId;

        function let(
            AnswerRepository $answers,
            IsActive $isActive,
            OwnedByRequester $byRequester,
            EventDispatcherInterface $dispatcher,
            Answer $answer,
            AnswerWasRemoved $event
        ) {

            $this->answerId = new Answer\AnswerId();
            $answers->withAnswerId($this->answerId)->willReturn($answer);
            $answers->remove($answer)->willReturnArgument();

            $answer->releaseEvents()->willReturn([$event]);

            $isActive->isSatisfiedBy($answer)->willReturn(true);
            $byRequester->isSatisfiedBy($answer)->willReturn(true);

            $dispatcher->dispatch($event)->willReturnArgument();

            $this->beConstructedWith($answers, $isActive, $byRequester, $dispatcher);
        }

        function it_is_initializable()
        {
            $this->shouldHaveType(RemoveAnswerHandler::class);
        }

        function its_a_command_handler()
        {
            $this->shouldBeAnInstanceOf(CommandHandler::class);
        }

        function it_handles_remove_answer_command(
            AnswerRepository $answers,
            EventDispatcherInterface $dispatcher,
            RemoveAnswerCommand $command
        ) {
            $command->answerId()->willReturn($this->answerId);

            $this->handle($command)->shouldReturnAnInstanceOf(Answer::class);
        }

        function it_throws_exception_when_answer_is_not_owned_by_requester(
            OwnedByRequester $byRequester,
            Answer $answer,
            RemoveAnswerCommand $command
        ) {
            $byRequester->isSatisfiedBy($answer)->willReturn(false);
            $command->answerId()->willReturn($this->answerId);

            $this->shouldThrow(SpecificationFails::class)->during('handle', [$command]);
        }

        function it_throws_exception_when_answer_is_not_active(
            IsActive $isActive,
            Answer $answer,
            RemoveAnswerCommand $command
        ) {
            $isActive->isSatisfiedBy($answer)->willReturn(false);
            $command->answerId()->willReturn($this->answerId);

            $this->shouldThrow(SpecificationFails::class)->during('handle', [$command]);
        }
}
