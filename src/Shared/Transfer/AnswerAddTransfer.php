<?php

declare(strict_types=1);


namespace App\Shared\Transfer;

final readonly class AnswerAddTransfer
{
    public function __construct(
        public string $text,
        public bool $isCorrect
    )
    {
    }
}
