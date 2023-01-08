<?php

declare(strict_types=1);

namespace App\UserInterface\Answers;

use App\Domain\Answers\Answer\AnswerId;
use App\Domain\Answers\AnswerRepository;
use App\UserInterface\AuthenticationAwareController;
use App\UserInterface\AuthenticationAwareMethods;
use Slick\JSONAPI\Document\DocumentEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ReadAnswerController
 *
 * @package App\UserInterface\Answers
 */
final class ReadAnswerController extends AbstractController implements AuthenticationAwareController
{

    use AuthenticationAwareMethods;

    public function __construct(
        private readonly  AnswerRepository $answers,
        private readonly DocumentEncoder $documentEncoder
    ) {
    }

    #[Route(path: "/answers/{id}", methods: ['GET'])]
    public function read(string $id): Response
    {
        $answerId = new AnswerId($id);
        $answer = $this->answers->withAnswerId($answerId);
        return new Response(
            content: $this->documentEncoder->encode($answer),
            headers: ['content-type' => 'application/vnd.api+json']
        );
    }
}