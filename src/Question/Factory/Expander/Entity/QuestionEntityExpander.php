<?php

declare(strict_types=1);


namespace App\Question\Factory\Expander\Entity;

use App\Entity\Question;
use App\Shared\Transfer\QuestionAddTransfer;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final readonly class QuestionEntityExpander implements QuestionEntityExpanderInterface
{
    /**
     * @param iterable<QuestionEntityExpanderInterface> $questionEntityExpanderCollection
     */
    public function __construct(
        #[TaggedIterator(tag: 'question_entity_expander')]
        private iterable $questionEntityExpanderCollection
    )
    {
    }

    public function expand(Question $question, QuestionAddTransfer $questionAddTransfer): void
    {
        foreach ($this->questionEntityExpanderCollection as $expander) {
            $expander->expand($question, $questionAddTransfer);
        }
    }
}
