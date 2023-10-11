<?php

declare(strict_types=1);


namespace App\Quiz\UseCase;

use App\Quiz\Transformer\QuizTransferFromEntityTransformerInterface;
use App\Repository\QuizRepository;
use App\Shared\Transfer\QuizTransfer;

final readonly class QuizFindCurrentUseCase implements QuizFindCurrentUseCaseInterface
{
    public function __construct(
        private QuizRepository $quizRepository,
        private QuizTransferFromEntityTransformerInterface $quizTransferFromEntityTransformer
    )
    {
    }

    public function findCurrentQuizByUserId(string $userId): QuizTransfer|null
    {
        $quizEntity = $this->quizRepository->findIncompleteQuizByUserId($userId);
        if($quizEntity === null) {
            return null;
        }

        return $this->quizTransferFromEntityTransformer->transform($quizEntity);
    }
}
