<?php

declare(strict_types=1);


namespace App\Shared\Transfer;

final readonly class QuizCreatedTransfer
{
    public function __construct(
        public int $quizId
    )
    {
    }
}
