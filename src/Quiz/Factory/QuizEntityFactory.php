<?php

declare(strict_types=1);


namespace App\Quiz\Factory;

use App\Entity\Quiz;
use App\Quiz\Factory\Expander\Entity\QuizEntityExpanderInterface;
use App\Shared\Transfer\QuizInitTransfer;

final readonly class QuizEntityFactory implements QuizEntityFactoryInterface
{
    public function __construct(
        private QuizEntityExpanderInterface $entityExpander
    )
    {
    }

    public function create(QuizInitTransfer $quizInitTransfer): Quiz
    {
        $quiz = new Quiz();

        $this->entityExpander->expand($quiz, $quizInitTransfer);

        return $quiz;
    }
}
