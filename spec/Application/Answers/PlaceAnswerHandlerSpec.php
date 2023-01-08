<?php

namespace spec\App\Application\Answers;

use App\Application\CommandHandler;
use App\Application\Answers\PlaceAnswerCommand;
use App\Application\Answers\PlaceAnswerHandler;
use App\Domain\Answers\Events\AnswerWasPlaced;
use App\Domain\Answers\Answer;
use App\Domain\Answers\AnswerRepository;
use App\Domain\Questions\Question;
use App\Domain\UserManagement\User;
use App\Domain\UserManagement\UserRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;

class PlaceAnswerHandlerSpec extends ObjectBehavior
{
    private $userId;

    function let(
        UserRepository           $users,
        User                     $owner,
        AnswerRepository         $answers,
        EventDispatcherInterface $dispatcher
    )
    {

        $this->userId = new User\UserId();
        $users->withId($this->userId)->willReturn($owner);
        $owner->userId()->willReturn($this->userId);

        $answers->add(Argument::type(Answer::class))->willReturnArgument();

        $dispatcher->dispatch(Argument::type(AnswerWasPlaced::class))->willReturnArgument();

        $this->beConstructedWith($users, $answers, $dispatcher);
    }

   private Question\QuestionId $questionId;

    function it_is_initializable()
    {
        $this->shouldHaveType(PlaceAnswerHandler::class);
    }

    function its_a_command_handler()
    {
        $this->shouldBeAnInstanceOf(CommandHandler::class);
    }

    function it_handles_place_answer_command(
        AnswerRepository         $answers,
        EventDispatcherInterface $dispatcher
    )
    {
        $command = new PlaceAnswerCommand(
            $this->userId,
            $this->questionId,
            $this->answerId,
            $this->body,
        );

        $answer = $this->handle($command);
        $answer->shouldBeAnInstanceOf(Answer::class);

        $answers->add($answer)->shouldHaveBeenCalled();
        $dispatcher->dispatch(Argument::type(AnswerWasPlaced::class))->shouldHaveBeenCalled();

    }
}

