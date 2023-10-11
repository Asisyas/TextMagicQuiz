<?php

namespace App\Controller;

use App\Form\QuizQuestionAnswerType;
use App\Quiz\Exception\QuizInitFailedException;
use App\Quiz\QuizFacadeInterfaceAndQuizIdBy;
use App\Shared\Transfer\QuizInitTransfer;
use App\Shared\Transfer\QuizQuestionAnswerTransfer;
use App\Shared\Transfer\QuizQuestionTransfer;
use App\Shared\Transfer\QuizTransfer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    public function __construct(
        private readonly QuizFacadeInterfaceAndQuizIdBy $quizFacade
    )
    {
    }

    #[Route('/quiz', name: 'app_quiz')]
    public function index(Request $request): Response
    {
        $quizInitTransfer = new QuizInitTransfer(
            $this->getUser()->getUserIdentifier()
        );
        $answerIndex = 1;
        $quizId = null;
        try {
            $quizCreated = $this->quizFacade->quizInit($quizInitTransfer);
            $quizId = $quizCreated->quizId;
            // This is normal behavior. One user can take only one quiz at a time.
            //If user has not completed the quiz, he will continue to take the one he has not yet completed.
        } catch (QuizInitFailedException $exception) {
            $quiz = $this->quizFacade->findCurrentQuizByUserId($this->getUser()->getUserIdentifier());
            $quizId = $quiz->quizId;
        }

        $request->getSession()->set('quiz_id', $quizId);

        return new RedirectResponse($this->generateUrl('app_quiz_show', [
            'questionIndex' => $answerIndex,
        ]));
    }

    #[Route('/quiz/{questionIndex}',
        name: 'app_quiz_show',
        requirements: ["questionIndex" => "\d+"],
        methods: [Request::METHOD_GET])
    ]
    public function showQuiz(Request $request, int $questionIndex): Response
    {
        $quiz_id = $request->getSession()->get('quiz_id');
        $quiz = $this
            ->quizFacade
            ->findQuizByUserAndQuizId($this->getUser()->getUserIdentifier(), $quiz_id)
        ;
        $question = $this->lookupQuizQuestionByIndex($quiz, $questionIndex);
        $quizQuestionAnswerType = $this->createQuizQuestionForm($question);
        return $this->render(
            'quiz/index.html.twig',
            [
                'quiz' => $quiz,
                'question_index' => $questionIndex,
                'question' => $question,
                'question_answers_form' => $quizQuestionAnswerType->createView()
            ]
        );
    }

    #[Route(
        '/quiz/{questionIndex}',
        name: 'app_quiz_answer',
        requirements: ["questionIndex" => "\d+"],
        methods: [Request::METHOD_POST])]
    public function answerQuizQuestion(Request $request, int $questionIndex): Response
    {
        $quiz = $this->quizFacade->findCurrentQuizByUserId($this->getUser()->getUserIdentifier());
        $question = $this->lookupQuizQuestionByIndex($quiz, $questionIndex);
        $quizQuestionAnswerType = $this->createQuizQuestionForm($question);
        $quizQuestionAnswerType->handleRequest($request);
        if (!$quizQuestionAnswerType->isSubmitted() || !$quizQuestionAnswerType->isValid()) {
            throw new BadRequestHttpException();
        }

        $questionAnswerTransfer = $quizQuestionAnswerType->getData();
        $this->quizFacade->makeQuestionAnswer($questionAnswerTransfer, $this->getUser()->getUserIdentifier());

        return $this->redirectToRoute('app_quiz_show', [
            'questionIndex' => $questionIndex,
        ]);
    }

    private function createQuizQuestionForm(QuizQuestionTransfer $quizQuestionTransfer): FormInterface
    {
        $questionAnswerTransfer = new QuizQuestionAnswerTransfer(
            [],
            $quizQuestionTransfer->id
        );

        return $this->createForm(QuizQuestionAnswerType::class, $questionAnswerTransfer);
    }

    private function lookupQuizQuestionByIndex(QuizTransfer $quiz, int $index): QuizQuestionTransfer
    {
        $questions = $quiz->questions;
        $indexReal = $index - 1;
        if ($indexReal < 0 && count($questions) > $index) {
            throw new NotFoundHttpException('Invalid question id.');
        }

        return $quiz->questions[$indexReal];
    }
}
