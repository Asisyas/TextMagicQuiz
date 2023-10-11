<?php

declare(strict_types=1);


namespace App\Quiz\Factory\Expander\Entity;

use App\Entity\Quiz;
use App\Shared\Transfer\QuizInitTransfer;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final readonly class QuizEntityExpander implements QuizEntityExpanderInterface
{
    /**
     * @param iterable<QuizEntityExpanderInterface> $quizEntityExpanderCollection
     */
    public function __construct(
        #[TaggedIterator(tag: 'quiz_entity_expander')]
        private iterable $quizEntityExpanderCollection
    )
    {
    }

    public function expand(Quiz $quiz, QuizInitTransfer $quizInitTransfer): void
    {
        foreach ($this->quizEntityExpanderCollection as $expander) {
            $expander->expand($quiz, $quizInitTransfer);
        }
    }
}
