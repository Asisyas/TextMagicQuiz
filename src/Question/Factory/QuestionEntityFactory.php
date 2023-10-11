<?php

declare(strict_types=1);

namespace App\Question\Factory;

use App\Entity\Question;
use App\Question\Factory\Expander\Entity\QuestionEntityExpanderInterface;
use App\Shared\Transfer\QuestionAddTransfer;

final readonly class QuestionEntityFactory implements QuestionEntityFactoryInterface
{
    public function __construct(
        private QuestionEntityExpanderInterface $questionEntityExpander
    )
    {
    }

    public function create(QuestionAddTransfer $questionAddTransfer): Question
    {
        $question = new Question();
        $this->questionEntityExpander->expand($question, $questionAddTransfer);

        return $question;
    }
}
