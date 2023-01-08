<?php

namespace spec\App\Domain\Answers\Events;

use App\Domain\Answers\Events\AnswerWasPlaced;
use PhpSpec\ObjectBehavior;
use App\Domain\DomainEvent;
use App\Domain\UserManagement\User\UserId;
use DateTimeImmutable;
use JsonSerializable;
use App\Domain\Answers\Answer\AnswerId;

class AnswerWasPlacedSpec extends ObjectBehavior
{
    private $ownerUserId;
    private $answerId;
    private $body;

    function let()
    {
        $this->ownerUserId = new UserId();
        $this->answerId = new AnswerId();
        $this->body = 'A long text as body...';
        $this->beConstructedWith(
            $this->ownerUserId,
            $this->answerId,
            $this->body
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AnswerWasPlaced::class);
    }

    function it_has_a_ownerUserId()
    {
        $this->ownerUserId()->shouldBe($this->ownerUserId);
    }

    function it_has_a_answerId()
    {
        $this->answerId()->shouldBe($this->answerId);
    }

    function it_has_a_body()
    {
        $this->body()->shouldBe($this->body);
    }

    function it_is_a_domain_event()
    {
        $this->shouldBeAnInstanceOf(DomainEvent::class);
    }

    function it_is_json_serializable()
    {
        $this->shouldBeAnInstanceOf(JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'ownerUserId' => $this->ownerUserId,
            'answerId' => $this->answerId,
            'body' => $this->body
        ]);
    }

    function it_has_an_occurred_on_date()
    {
        $this->occurredOn()->shouldBeAnInstanceOf(DateTimeImmutable::class);
    }
}
