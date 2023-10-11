<?php

declare(strict_types=1);


namespace App\Quiz\UseCase;

use App\Quiz\Transformer\QuizTransferFromEntityTransformerInterface;
use App\Repository\QuizRepository;
use App\Shared\Transfer\QuizTransfer;

final readonly class QuizFindByUserAndQuizIdUseCase implements QuizFindByUserAndQuizIdUseCaseInterface
{
    public function __construct(
        private QuizRepository $quizRepository,
        private QuizTransferFromEntityTransformerInterface $quizTransferFromEntityTransformer
    )
    {
    }

    public function findQuizByUserAndQuizId(string $username, int $quizId): QuizTransfer
    {
        $quiz = $this->quizRepository->findQuizByUserAndQuizId($username, $quizId);

        return $this->quizTransferFromEntityTransformer->transform($quiz);
    }
}
