<?php

declare(strict_types=1);

namespace App\Quiz\Transformer\Expander;

use App\Shared\Transfer\QuizAnswerVariantTransfer;
use App\Shared\Transfer\QuizQuestionTransfer;
use App\Shared\Transfer\QuizTransfer;

final readonly class QuizTransferQuestionExpander implements QuizTransferExpanderInterface
{
    public function expand(QuizTransfer $quizTransfer, iterable $quizContentData): void
    {
        $answerCorrectId = $quizContentData['correct_answer_id'];
        $answerPossibleId = $quizContentData['possible_answer_id'];
        $answerPossibleText = $quizContentData['possible_answer_text'];
        $answerIdsUser = $quizContentData['user_answer_id'];
        $isCorrect = $quizContentData['is_correct'];

        $question = $this->findOrCreateQuestionInQuizTransfer($quizTransfer, $quizContentData);
        if($question->isCorrect || $question->isCorrect === null) {
            $question->isCorrect = $isCorrect;
        }

        $this->appendItemToArrayIfNoExists($question->variants, new QuizAnswerVariantTransfer(
            $answerPossibleId,
            $answerPossibleText,
        ));
        $this->appendItemToArrayIfNoExists($question->answers, $answerIdsUser);
        $this->appendItemToArrayIfNoExists($question->variantsCorrect, $answerCorrectId);
    }

    private function appendItemToArrayIfNoExists(array &$source, mixed $value): void
    {
        if($value && !in_array($value, $source)) {
            $source[] = $value;
        }
    }

    private function findOrCreateQuestionInQuizTransfer(QuizTransfer $quizTransfer, $quizContentData): QuizQuestionTransfer
    {
        $questionId = $quizContentData['question_id'];
        foreach ($quizTransfer->questions as $question) {
            if ($question->id === $questionId) {
                return $question;
            }
        }

        $questionText = $quizContentData['question_text'];
        $quizQuestionTransfer = new QuizQuestionTransfer(
            $questionId,
            $questionText,
            [],
            [],
            [],
            null
        );
        $quizTransfer->questions[] = $quizQuestionTransfer;

        return $quizQuestionTransfer;
    }
}
