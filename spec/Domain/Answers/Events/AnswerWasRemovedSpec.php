<?php

namespace spec\App\Domain\Answers\Events;

use App\Domain\Answers\Events\AnswerWasRemoved;
use PhpSpec\ObjectBehavior;
use App\Domain\Answers\Answer\AnswerId;
use App\Domain\AbstractEvent;
use App\Domain\DomainEvent;

class AnswerWasRemovedSpec extends ObjectBehavior
{
    private $answerId;

    function let()
    {
        $this->answerId = new AnswerId();
        $this->beConstructedWith($this->answerId);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AnswerWasRemoved::class);
    }

    function its_an_event()
    {
        $this->shouldBeAnInstanceOf(DomainEvent::class);
        $this->shouldHaveType(AbstractEvent::class);
        $this->occurredOn()->shouldBeAnInstanceOf(\DateTimeImmutable::class);
    }

    function it_has_a_answerId()
    {
        $this->answerId()->shouldBe($this->answerId);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'answerId' => $this->answerId
        ]);
    }
}
