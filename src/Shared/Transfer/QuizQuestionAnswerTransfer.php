<?php

declare(strict_types=1);


namespace App\Shared\Transfer;

final class QuizQuestionAnswerTransfer
{
    /**
     * @param int[] $answerIds
     * @param int $questionId
     */
    public function __construct(
        public array $answerIds,
        public int   $questionId
    )
    {
    }
}
