<?php

declare(strict_types=1);

namespace App\UserInterface\Answers;

use App\Application\Answers\ChangeAnswerCommand;
use App\Application\Answers\ChangeAnswerHandler;
use App\UserInterface\AuthenticationAwareController;
use App\UserInterface\AuthenticationAwareMethods;
use Doctrine\ORM\EntityManagerInterface;
use Slick\JSONAPI\Document\DocumentDecoder;
use Slick\JSONAPI\Document\DocumentEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ChangeAnswer
 *
 * @package App\UserInterface\Answers
 */
final class ChangeAnswer extends AbstractController implements AuthenticationAwareController
{
    use AuthenticationAwareMethods;

    public function __construct(
        private readonly DocumentDecoder $documentDecoder,
        private readonly ChangeAnswerHandler $answerHandler,
        private readonly DocumentEncoder $documentEncoder,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route(path: "/answers/{answerId}", methods: ["PATCH", "POST"])]
    public function handle(string $answerId): Response
    {
        $command = $this->documentDecoder->decodeTo(ChangeAnswerCommand::class);
        $answer = $this->answerHandler->handle($command);
        $this->entityManager->flush();

        return new Response(
            content: $this->documentEncoder->encode($answer),
            headers: [
                'content-type' => 'application/vnd.api+json'
            ]
        );
    }
}