<?php

namespace spec\App\Application\Answers;

use App\Application\Answers\RemoveAnswerCommand;
use PhpSpec\ObjectBehavior;
use App\Domain\Answers\Answer\AnswerId;
use App\Application\Command;

class RemoveAnswerCommandSpec extends ObjectBehavior
{
    private $answerId;

    function let()
    {
        $this->answerId = new AnswerId();
        $this->beConstructedWith($this->answerId);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RemoveAnswerCommand::class);
    }

    function its_a_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
    }

    function it_has_a_answerId()
    {
        $this->answerId()->shouldBe($this->answerId);
    }
}