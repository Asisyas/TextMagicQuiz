<?php

declare(strict_types=1);

namespace App\Quiz;

use App\Quiz\UseCase\QuizFindByUserAndQuizIdUseCaseInterface;
use App\Quiz\UseCase\QuizFindCurrentUseCaseInterface;
use App\Quiz\UseCase\QuizInitUseCaseInterface;
use App\Quiz\UseCase\QuizQuestionAnswerUseCaseInterface;
use App\Shared\Transfer\QuizCreatedTransfer;
use App\Shared\Transfer\QuizInitTransfer;
use App\Shared\Transfer\QuizQuestionAnswerTransfer;
use App\Shared\Transfer\QuizQuestionTransfer;
use App\Shared\Transfer\QuizTransfer;

final readonly class QuizFacade implements QuizFacadeInterfaceAndQuizIdBy
{
    public function __construct(
        private QuizInitUseCaseInterface                $quizInitUseCase,
        private QuizFindCurrentUseCaseInterface         $quizGetCurrentUseCase,
        private QuizQuestionAnswerUseCaseInterface      $quizQuestionAnswerUseCase,
        private QuizFindByUserAndQuizIdUseCaseInterface $quizGetByUserUseCase
    )
    {
    }

    public function quizInit(QuizInitTransfer $quizInitTransfer): QuizCreatedTransfer
    {
        return $this->quizInitUseCase->quizInit($quizInitTransfer);
    }

    public function findCurrentQuizByUserId(string $userId): QuizTransfer|null
    {
        return $this->quizGetCurrentUseCase->findCurrentQuizByUserId($userId);
    }

    public function makeQuestionAnswer(QuizQuestionAnswerTransfer $quizQuestionAnswerTransfer, string $userId): void
    {
        $this->quizQuestionAnswerUseCase->makeQuestionAnswer($quizQuestionAnswerTransfer, $userId);
    }

    public function findQuizByUserAndQuizId(string $username, int $quizId): QuizTransfer
    {
        return $this->quizGetByUserUseCase->findQuizByUserAndQuizId($username, $quizId);
    }
}
