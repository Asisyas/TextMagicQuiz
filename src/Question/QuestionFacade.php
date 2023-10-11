<?php

declare(strict_types=1);


namespace App\Question;

use App\Question\UseCase\QuestionAddUseCaseInterface;
use App\Question\UseCase\QuestionCheckAnswersUseCaseInterface;
use App\Question\UseCase\QuestionLookupUseCaseInterface;
use App\Shared\Transfer\QuestionAddTransfer;
use App\Shared\Transfer\QuizQuestionAnswerTransfer;
use Doctrine\Common\Collections\Collection;

final readonly class QuestionFacade implements QuestionFacadeInterface
{
    public function __construct(
        private QuestionAddUseCaseInterface $questionAddUseCase,
        private QuestionLookupUseCaseInterface $questionLookupUseCase,
        private QuestionCheckAnswersUseCaseInterface $questionCheckAnswersUseCase
    )
    {
    }

    public function createQuestion(QuestionAddTransfer $questionAddTransfer): void
    {
        $this->questionAddUseCase->createQuestion($questionAddTransfer);
    }

    public function lookupQuestions(int $count): Collection
    {
        return $this->questionLookupUseCase->lookupQuestions($count);
    }

    public function checkUserAnswers(QuizQuestionAnswerTransfer $quizQuestionAnswerTransfer): bool
    {
        return $this->questionCheckAnswersUseCase->checkUserAnswers($quizQuestionAnswerTransfer);
    }
}
