<?php

declare(strict_types=1);


namespace App\Quiz\UseCase;

use App\Shared\Transfer\QuizTransfer;

interface QuizFindCurrentUseCaseInterface
{
    public function findCurrentQuizByUserId(string $userId): QuizTransfer|null;
}
