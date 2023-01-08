<?php

namespace spec\App\Application\Answers;

use App\Application\CommandHandler;
use App\Application\Answers\ChangeAnswerCommand;
use App\Application\Answers\ChangeAnswerHandler;
use App\Domain\Exception\SpecificationFails;
use App\Domain\Answers\Events\AnswerWasChanged;
use App\Domain\Answers\Answer;
use App\Domain\Answers\AnswerRepository;
use App\Domain\Answers\Specification\OwnedByRequester;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ChangeAnswerHandlerSpec extends ObjectBehavior
{

    private $answerId;
    private $body;

    function let(
        AnswerRepository $answers,
        OwnedByRequester $ownedByRequester,
        EventDispatcher $dispatcher,
        Answer $answer,
        AnswerWasChanged $event
    ) {
        $this->answerId = new Answer\AnswerId();
        $this->body = 'some body';

        $answers->withAnswerId($this->answerId)->willReturn($answer);

        $answer->answerId()->willReturn($this->answerId);
        $answer->change($this->body)->willReturn($answer);
        $answer->releaseEvents()->willReturn([$event]);

        $dispatcher->dispatch(Argument::any())->willReturnArgument();

        $ownedByRequester->isSatisfiedBy($answer)->willReturn(true);

        $this->beConstructedWith($answers, $ownedByRequester, $dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ChangeAnswerHandler::class);
    }

    function its_a_command_handler()
    {
        $this->shouldBeAnInstanceOf(CommandHandler::class);
    }

    function it_changes_an_answer(
        Answer $answer,
    ) {
        $command = new ChangeAnswerCommand(
            $this->answerId,
            $this->body
        );

        $this->handle($command)->shouldReturn($answer);
        $answer->change($this->body)->shouldHaveBeenCalled();
    }

    function it_throws_an_exception_if_answer_is_not_owned_by_requester(
        OwnedByRequester $ownedByRequester,
        Answer $answer
    ) {
        $command = new ChangeAnswerCommand(
            $this->answerId,
            $this->body
        );

        $ownedByRequester->isSatisfiedBy($answer)->willReturn(false);

        $this->shouldThrow(SpecificationFails::class)->during('handle', [$command]);
    }
}
