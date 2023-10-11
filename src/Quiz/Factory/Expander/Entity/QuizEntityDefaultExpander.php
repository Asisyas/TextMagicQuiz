<?php

declare(strict_types=1);


namespace App\Quiz\Factory\Expander\Entity;

use App\Entity\Quiz;
use App\Shared\Transfer\QuizInitTransfer;

final readonly class QuizEntityDefaultExpander implements QuizEntityExpanderInterface
{
    public function expand(Quiz $quiz, QuizInitTransfer $quizInitTransfer): void
    {
        $quiz
            ->setStartedAt(new \DateTimeImmutable())
            ->setUserId($quizInitTransfer->userId);
    }
}
