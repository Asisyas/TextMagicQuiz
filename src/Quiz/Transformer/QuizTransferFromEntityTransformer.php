<?php

declare(strict_types=1);


namespace App\Quiz\Transformer;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\QuizAnswer;
use App\Quiz\Transformer\Expander\QuizTransferExpanderInterface;
use App\Repository\QuizRepository;
use App\Shared\Transfer\QuizAnswerVariantTransfer;
use App\Shared\Transfer\QuizQuestionTransfer;
use App\Shared\Transfer\QuizTransfer;

final readonly class QuizTransferFromEntityTransformer implements QuizTransferFromEntityTransformerInterface
{

    public function __construct(
        private QuizRepository $quizRepository,
        private QuizTransferExpanderInterface $quizTransferExpander
    )
    {
    }

    public function transform(Quiz $quiz): QuizTransfer
    {
        $quizTransfer = new QuizTransfer(
            $quiz->getUserId(),
            $quiz->getId(),
            $quiz->getStatus(),
            []
        );

        $quizContentArray = $this->quizRepository->findQuizQuestionsContentByQuizId($quiz->getId());
        $this->quizTransferExpander->expand($quizTransfer, $quizContentArray);

        return $quizTransfer;
    }
}
