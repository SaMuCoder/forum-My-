<?php

namespace spec\App\Domain\Comments;

use App\Domain\Comments\Comment;
use PhpSpec\ObjectBehavior;
use App\Domain\RootAggregate;
use App\Domain\UserManagement\User;
use Doctrine\Common\Collections\Collection;
use App\Domain\Comments\Events\CommentWasPlaced;
use App\Domain\Comments\Events\CommentWasChanged;
use App\Domain\Answers\Answer;


class CommentSpec extends ObjectBehavior
{
}
