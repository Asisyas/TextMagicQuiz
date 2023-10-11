<?php

declare(strict_types=1);


namespace App\Question\Factory\Expander\Entity;

use App\Entity\Answer;
use App\Entity\Question;
use App\Shared\Transfer\AnswerAddTransfer;
use App\Shared\Transfer\QuestionAddTransfer;

final readonly class QuestionEntityAnswerExpander implements QuestionEntityExpanderInterface
{

    public function expand(Question $question, QuestionAddTransfer $questionAddTransfer): void
    {
        foreach ($questionAddTransfer->answers as $answerTransfer) {
           $this->fillAnswer($question, $answerTransfer);
        }
    }

    private function fillAnswer(Question $question, AnswerAddTransfer $answerAddTransfer): void
    {
        $answer = new Answer(
            $question,
            $answerAddTransfer->text
        );

        $question->addAnswer($answer);
        if(!$answerAddTransfer->isCorrect) {
            return;
        }

        $question
            ->getAnswerCorrectCombinations()
            ->addAnswer($answer);

        $answer->setAnswerCorrectCombinations($question->getAnswerCorrectCombinations());
    }


}
