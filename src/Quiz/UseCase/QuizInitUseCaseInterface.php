<?php

declare(strict_types=1);


namespace App\Quiz\UseCase;

use App\Quiz\Exception\QuizInitFailedException;
use App\Shared\Transfer\QuizCreatedTransfer;
use App\Shared\Transfer\QuizInitTransfer;

interface QuizInitUseCaseInterface
{
    /**
     * @throws QuizInitFailedException
     */
    public function quizInit(QuizInitTransfer $quizInitTransfer): QuizCreatedTransfer;
}
