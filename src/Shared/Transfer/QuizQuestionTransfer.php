<?php

declare(strict_types=1);


namespace App\Shared\Transfer;

final class QuizQuestionTransfer
{
    /**
     * @param int[]|null $answers
     * @param QuizAnswerVariantTransfer[] $variants
     */
    public function __construct(
        public readonly int $id,
        public readonly string $text,
        public array|null $answers,
        public array $variants,
        public array $variantsCorrect,
        public bool|null $isCorrect
    )
    {
    }
}
