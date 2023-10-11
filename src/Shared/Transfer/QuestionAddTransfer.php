<?php

declare(strict_types=1);


namespace App\Shared\Transfer;

final readonly class QuestionAddTransfer
{
    public iterable $answers;
    /**
     * @param string $text
     * @param array<AnswerAddTransfer> $answers
     */
    public function __construct(
        public string $text,
        AnswerAddTransfer ...$answers
    )
    {
        $this->answers = $answers;
    }
}
