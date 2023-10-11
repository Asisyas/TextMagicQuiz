<?php

declare(strict_types=1);


namespace App\Question\Factory\Expander\Entity;

use App\Entity\Question;
use App\Shared\Transfer\QuestionAddTransfer;

final readonly class QuestionEntityDefaultExpander implements QuestionEntityExpanderInterface
{

    public function expand(Question $question, QuestionAddTransfer $questionAddTransfer): void
    {
        $question->setText($questionAddTransfer->text);
    }
}
