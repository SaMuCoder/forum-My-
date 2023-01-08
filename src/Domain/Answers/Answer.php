<?php

namespace App\Domain\Answers;

use App\Domain\Answers\Answer\AnswerId;
use App\Domain\Answers\Events\AnswerWasChanged;
use App\Domain\Answers\Events\AnswerWasPlaced;
use App\Domain\RootAggregate;
use App\Domain\RootAggregateMethods;
use App\Domain\UserManagement\User;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\AsResourceObject;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\Attribute;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\Relationship;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\ResourceIdentifier;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;

#[
    Entity,
    Table(name: "answers")
]
#[AsResourceObject(type: 'answers', links: [AsResourceObject::LINK_SELF], isCompound: true)]
class Answer implements JsonSerializable, RootAggregate
{
    use RootAggregateMethods;

    #[Id, GeneratedValue(strategy: 'NONE'), Column(name: 'id', type: 'AnswerId')]
    #[ResourceIdentifier]
    private AnswerId $answerId;

    public function __construct(
        #[ManyToOne(targetEntity: User::class, fetch: "EAGER")]
        #[JoinColumn(name: "owner_id", onDelete: "CASCADE")]
        #[Relationship(
            type: Relationship::TO_ONE,
            links: [AsResourceObject::LINK_RELATED],
            meta: ['description' => "An answer is owned by a user with is it owner."])
        ]
        private User   $owner,
        #[Column]
        #[Attribute]
        private string $body,
        //isClosed
        #[Column]
        #[Attribute]
        private bool   $isAccepted = false,

    )
    {
        $this->answerId = new AnswerId();
        $this->answers = new ArrayCollection();

        $this->recordThat(new AnswerWasPlaced(
            $this->owner->userId(),
            $this->answerId,
            $this->body,
        ));
    }

    /**
     * answerId
     *
     * @return AnswerId
     */
    public function answerId(): AnswerId
    {
        return $this->answerId;
    }

    /**
     * owner
     *
     * @return User
     */
    public function owner(): User
    {
        return $this->owner;
    }

    /**
     * body
     *
     * @return string
     */
    public function body(): string
    {
        return $this->body;
    }

    /**
     * isClosed
     *
     * @return bool
     */
    public function isAccepted(): bool
    {
        return $this->isAccepted;
    }

    /**
     * answers
     *
     * @return Collection
     */
    public function answers(): Collection
    {
        if ($this->answers) {
            $this->answers = new ArrayCollection();
        }
        return $this->answers;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): mixed
    {
        return [
            'answerId' => $this->answerId,
            'owner' => $this->owner,
            'body' => $this->body,
        ];
    }

    public function change(?string $body): self
    {
        $this->body = $body ?: $this->body;
        $this->recordThat(new AnswerWasChanged(
            $this->answerId,
            $this->body,
        ));
        return $this;
    }
}