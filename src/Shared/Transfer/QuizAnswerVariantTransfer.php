<?php

declare(strict_types=1);


namespace App\Shared\Transfer;

final readonly class QuizAnswerVariantTransfer
{
    public function __construct(
        public int $id,
        public string $text,
    )
    {
    }
}
