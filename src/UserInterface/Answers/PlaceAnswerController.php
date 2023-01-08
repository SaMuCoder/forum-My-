<?php

declare(strict_types=1);

namespace App\UserInterface\Answers;

use App\Application\Answers\PlaceAnswerCommand;
use App\Application\Answers\PlaceAnswerHandler;
use App\UserInterface\AuthenticationAwareController;
use App\UserInterface\AuthenticationAwareMethods;
use Doctrine\ORM\EntityManagerInterface;
use Slick\JSONAPI\Document\DocumentDecoder;
use Slick\JSONAPI\Document\DocumentEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * PlaceAnswerController
 *
 * @package App\UserInterface\Answers
 */
final class PlaceAnswerController extends AbstractController implements AuthenticationAwareController
{

    use AuthenticationAwareMethods;

    public function __construct(
        private readonly PlaceAnswerHandler $handler,
        private readonly DocumentDecoder $documentDecoder,
        private readonly DocumentEncoder $documentEncoder,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route(path: "/answers", methods: "POST")]
    public function handle(): Response
    {
        /** @var PlaceAnswerCommand $command */
        $command = $this->documentDecoder->decodeTo(PlaceAnswerCommand::class);
        $answer = $this->handler->handle($command);
        $this->entityManager->flush();
        return new Response(
            content: $this->documentEncoder->encode($answer),
            status: 201,
            headers: [
                "Content-Type" => "application/vnd.api+json",
                "location" => "/answers/{$answer->answerId()}"
            ]
        );
    }
}