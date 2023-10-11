<?php

declare(strict_types=1);


namespace App\Question\UseCase;

use App\Shared\Transfer\QuizQuestionAnswerTransfer;

interface QuestionCheckAnswersUseCaseInterface
{
    public function checkUserAnswers(QuizQuestionAnswerTransfer $quizQuestionAnswerTransfer): bool;
}
