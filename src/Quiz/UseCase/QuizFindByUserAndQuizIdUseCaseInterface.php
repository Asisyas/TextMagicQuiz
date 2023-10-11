<?php

declare(strict_types=1);


namespace App\Quiz\UseCase;

use App\Shared\Transfer\QuizTransfer;
use Doctrine\ORM\EntityNotFoundException;

interface QuizFindByUserAndQuizIdUseCaseInterface
{
    /**
     * @throws EntityNotFoundException
     */
    public function findQuizByUserAndQuizId(string $username, int $quizId): QuizTransfer;
}
