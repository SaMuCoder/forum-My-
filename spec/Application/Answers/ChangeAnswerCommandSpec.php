<?php

namespace spec\App\Application\Answers;

use App\Application\Answers\ChangeAnswerCommand;
use PhpSpec\ObjectBehavior;
use App\Domain\UserManagement\User\UserId;
use App\Domain\Questions\Question\QuestionId;
use App\Domain\Answers\Answer\AnswerId;
use App\Application\Command;

class ChangeAnswerCommandSpec extends ObjectBehavior
{
    private $answerId;
    private $body;
    private $questionId;

    function let()
    {
        $this->answerId = new AnswerId();
        $this->questionId = new QuestionId();
        $this->body = 'Some text as body...';
        $this->beConstructedWith(
            $this->answerId,
            $this->body,
            $this->questionId
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ChangeAnswerCommand::class);
    }

    function its_a_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
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