<?php

declare(strict_types=1);

namespace App\Quiz\UseCase;

use App\Quiz\Exception\QuizAlreadyAnsweredException;
use App\Quiz\Exception\QuizNoActiveException;
use App\Quiz\Exception\QuizQuestionNotContainsAnswersException;
use App\Shared\Transfer\QuizQuestionAnswerTransfer;
use App\Shared\Transfer\QuizQuestionTransfer;

interface QuizQuestionAnswerUseCaseInterface
{
    /**
     * @throws QuizNoActiveException
     * @throws QuizQuestionNotContainsAnswersException
     * @throws QuizAlreadyAnsweredException
     */
    public function makeQuestionAnswer(
        QuizQuestionAnswerTransfer $quizQuestionAnswerTransfer,
        string $userId
    ): void;
}
