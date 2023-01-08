<?php

declare(strict_types=1);

namespace App\UserInterface\Answers;

use App\Application\Answers\RemoveAnswerCommand;
use App\Application\Answers\RemoveAnswerHandler;
use App\Domain\Answers\Answer\AnswerId;
use App\UserInterface\AuthenticationAwareController;
use App\UserInterface\AuthenticationAwareMethods;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * DeleteAnswer
 *
 * @package App\UserInterface\Answers
 */
final class DeleteAnswer extends AbstractController implements AuthenticationAwareController
{

    use AuthenticationAwareMethods;

    public function __construct(
        private readonly RemoveAnswerHandler $answerHandler,
        private readonly EntityManagerInterface $entityManager
    ) {

    }

    #[Route(path: "/answers/{answerId}", methods: ['DELETE'])]
    public function handle(string $answerId): Response
    {
        $answerId = new AnswerId($answerId);
        $command = new RemoveAnswerCommand($answerId);
        $this->answerHandler->handle($command);
        $this->entityManager->flush();
        return new Response(status: 204);
    }
}