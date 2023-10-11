<?php

declare(strict_types=1);


namespace App\Quiz\Exception;

final class QuizQuestionNotContainsAnswersException extends QuizException
{
    /**
     * @param int[] $answerIds
     */
    public function __construct(
        public readonly int $questionId,
        public readonly array $answerIds,
        int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'Question #%d dont contains answers with id\'s (%s)',
                $this->questionId,
                implode(',', $this->answerIds)),
            $code, $previous);
    }
}
