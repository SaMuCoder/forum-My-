<?php

namespace spec\App\Application\Answers;

use App\Application\Answers\PlaceAnswerCommand;
use PhpSpec\ObjectBehavior;
use App\Domain\UserManagement\User\UserId;
use App\Domain\Questions\Question\QuestionId;
use App\Domain\Answers\Answer\AnswerId;
use App\Application\Command;


class PlaceAnswerCommandSpec extends ObjectBehavior
{
    private $owner;
    private $questionId;
    private $answerId;
    private $body;

    function let()
    {
        $this->owner = new UserId();
        $this->questionId = new QuestionId();
        $this->answerId = new AnswerId();
        $this->body = 'Some text as body...';
        $this->beConstructedWith(
            $this->owner,
            $this->questionId,
            $this->answerId,
            $this->body
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PlaceAnswerCommand::class);
    }

    function its_a_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
    }

    function it_has_a_owner()
    {
        $this->owner()->shouldBe($this->owner);
    }

    function it_has_a_questionId()
    {
        $this->questionId()->shouldBe($this->questionId);
    }

    function it_has_a_answerId()
    {
        $this->answerId()->shouldBe($this->answerId);
    }

    function it_has_a_body()
    {
        $this->body()->shouldBe($this->body);
    }
}