<?php

declare(strict_types=1);


namespace App\Question\UseCase;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\Collection;

final readonly class QuestionLookupUseCase implements QuestionLookupUseCaseInterface
{
    public function __construct(
        private QuestionRepository $questionRepository
    )
    {
    }

    public function lookupQuestions(int $count): Collection
    {
        return $this->questionRepository->lookupRandomQuestions($count);
    }
}
