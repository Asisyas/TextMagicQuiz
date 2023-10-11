<?php

declare(strict_types=1);


namespace App\Quiz\UseCase;

use App\Quiz\Exception\QuizInitFailedException;
use App\Quiz\Factory\QuizEntityFactoryInterface;
use App\Repository\QuizRepository;
use App\Shared\Transfer\QuizCreatedTransfer;
use App\Shared\Transfer\QuizInitTransfer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class QuizInitUseCase implements QuizInitUseCaseInterface
{
    public function __construct(
        private ValidatorInterface         $validator,
        private QuizEntityFactoryInterface $quizEntityFactory,
        private QuizRepository             $quizRepository,
        private EntityManagerInterface     $entityManager,
    )
    {
    }

    public function quizInit(QuizInitTransfer $quizInitTransfer): QuizCreatedTransfer
    {
        $constraintViolationException = $this->validator->validate($quizInitTransfer);
        if ($constraintViolationException->count()) {
            throw new ValidationFailedException($quizInitTransfer, $constraintViolationException);
        }

        if ($this->quizRepository->userHasActiveQuiz($quizInitTransfer->userId)) {
            throw new QuizInitFailedException(
                'The user has an unfinished quiz. You cannot start a new one until you complete the current one.'
            );
        }

        $quizEntity = $this->quizEntityFactory->create($quizInitTransfer);
        $this->entityManager->persist($quizEntity);
        $this->entityManager->flush();

        return new QuizCreatedTransfer($quizEntity->getId()); // TODO: Make factory
    }
}
