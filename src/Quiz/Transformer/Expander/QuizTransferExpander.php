<?php

declare(strict_types=1);


namespace App\Quiz\Transformer\Expander;

use App\Entity\Quiz;
use App\Shared\Transfer\QuizTransfer;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final readonly class QuizTransferExpander implements QuizTransferExpanderInterface
{

    /**
     * @param iterable<QuizTransferExpanderInterface> $quizTransferExpanderCollection
     */
    public function __construct(
        #[TaggedIterator(tag: 'quiz_transfer_expander')]
        private iterable $quizTransferExpanderCollection
    )
    {
    }

    public function expand(QuizTransfer $quizTransfer, iterable $quizContentData): void
    {
        foreach ($this->quizTransferExpanderCollection as $expander) {
            foreach ($quizContentData as $quizItem) {
                $expander->expand($quizTransfer, $quizItem);
            }
        }
    }
}
