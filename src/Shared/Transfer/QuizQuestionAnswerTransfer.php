<?php

declare(strict_types=1);

namespace App\Shared\Transfer;

use Symfony\Component\Validator\Constraints as Assert;

final class QuizQuestionAnswerTransfer
{
    /**
     * @param int[] $answerIds
     * @param int $questionId
     */
    public function __construct(
        #[Assert\NotBlank(message: 'Choose at least one answer option.')]
        public array $answerIds,

        public int   $questionId
    )
    {
    }
}
