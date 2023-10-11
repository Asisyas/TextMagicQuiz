<?php

declare(strict_types=1);


namespace App\Quiz\Factory\Expander\Entity;

use App\Entity\Quiz;
use App\Repository\QuestionRepository;
use App\Shared\Transfer\QuizInitTransfer;

final readonly class QuizEntityQuestionsExpander implements QuizEntityExpanderInterface
{
    public function __construct(
        private QuestionRepository $questionRepository,
        private int $quizQuestionsCount = 10
    )
    {
    }

    public function expand(Quiz $quiz, QuizInitTransfer $quizInitTransfer): void
    {
        $questions = $this->questionRepository->lookupRandomQuestions($this->quizQuestionsCount);
        if(!$questions) {
            throw new \RuntimeException('TODO: Create concrete exception if questions was not found');
        }

        foreach ($questions as $question) {
            $quiz->addQuestion($question);
        }
    }
}
