<?php

declare(strict_types=1);


namespace App\Question\UseCase;

use App\Repository\QuestionRepository;
use App\Shared\Transfer\QuizQuestionAnswerTransfer;

final readonly class QuestionCheckAnswersUseCase implements QuestionCheckAnswersUseCaseInterface
{
    public function __construct(
        private QuestionRepository $questionRepository
    )
    {
    }

    public function checkUserAnswers(QuizQuestionAnswerTransfer $quizQuestionAnswerTransfer): bool
    {
        return $this
            ->questionRepository
            ->checkUserAnswersForCorrect(
                $quizQuestionAnswerTransfer->questionId,
                $quizQuestionAnswerTransfer->answerIds
            );
    }
}
