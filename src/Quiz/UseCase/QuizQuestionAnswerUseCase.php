<?php

declare(strict_types=1);

namespace App\Quiz\UseCase;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\QuizAnswer;
use App\Question\UseCase\QuestionCheckAnswersUseCaseInterface;
use App\Quiz\Exception\QuizAlreadyAnsweredException;
use App\Quiz\Exception\QuizNoActiveException;
use App\Quiz\Exception\QuizQuestionNotContainsAnswersException;
use App\Quiz\Status\StatusEnum;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
use App\Shared\Transfer\QuizQuestionAnswerTransfer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

final readonly class QuizQuestionAnswerUseCase implements QuizQuestionAnswerUseCaseInterface
{
    public function __construct(
        private QuizRepository                       $quizRepository,
        private AnswerRepository                     $answerRepository,
        private QuestionCheckAnswersUseCaseInterface $questionCheckAnswersUseCase,
        private EntityManagerInterface               $entityManager,
    )
    {
    }

    public function makeQuestionAnswer(QuizQuestionAnswerTransfer $quizQuestionAnswerTransfer, string $userId): void
    {
        $quizEntity = $this->quizRepository->findIncompleteQuizByUserId($userId);
        if (!$quizEntity) {
            throw new QuizNoActiveException($userId);
        }

        $questionEntity = $quizEntity->getQuestions()->filter(
            fn(Question $question) => $question->getId() == $quizQuestionAnswerTransfer->questionId
        )->first();

        $answersEntityCollection = $this->getAnswersFromQuestion($quizQuestionAnswerTransfer, $questionEntity);
        $quizAnswer = new QuizAnswer(
            $questionEntity,
            $quizEntity,
            $answersEntityCollection,
            $this->questionCheckAnswersUseCase->checkUserAnswers($quizQuestionAnswerTransfer),
        );

        $quizEntity->addQuizAnswer($quizAnswer);
        if ($this->checkIsQuizComplete($quizEntity)) {
            $quizEntity->setStatus(StatusEnum::COMPLETED);
        }

        try {
            $this->entityManager->persist($quizAnswer);
            $this->entityManager->persist($quizEntity);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new QuizAlreadyAnsweredException(
                'Can\'t answer the question again',
                0,
                $exception
            );
        }
    }

    private function checkIsQuizComplete(Quiz $quizEntity): bool
    {
        return $quizEntity->getQuestions()->count() === $quizEntity->getQuizAnswers()->count();
    }

    /**
     * @return Collection<Answer>
     *
     * @throws QuizQuestionNotContainsAnswersException
     */
    private function getAnswersFromQuestion(
        QuizQuestionAnswerTransfer $quizQuestionAnswerTransfer,
        Question                   $questionEntity
    ): Collection
    {
        $filteredAnswers = $this->answerRepository->filterAnswersByQuestionId(
            $questionEntity->getId(),
            $quizQuestionAnswerTransfer->answerIds,
        );

        if (count($filteredAnswers) !== count($quizQuestionAnswerTransfer->answerIds)) {
            $missingAnswers = array_diff(
                $quizQuestionAnswerTransfer->answerIds,
                array_map(fn(Answer $a) => $a->getId(), $filteredAnswers),
            );

            throw new QuizQuestionNotContainsAnswersException(
                $quizQuestionAnswerTransfer->questionId,
                $missingAnswers
            );
        }

        return new ArrayCollection($filteredAnswers);
    }
}
