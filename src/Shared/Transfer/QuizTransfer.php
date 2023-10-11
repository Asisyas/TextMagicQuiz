<?php

declare(strict_types=1);


namespace App\Shared\Transfer;

use App\Quiz\Status\StatusEnum;

final class QuizTransfer
{
    /**
     * @param QuizQuestionTransfer[] $questions
     */
    public function __construct(
        public readonly string $userId,
        public readonly int $quizId,
        public readonly StatusEnum $status,
        public array $questions
    )
    {
    }
}
