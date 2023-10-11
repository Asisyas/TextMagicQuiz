<?php

declare(strict_types=1);


namespace App\Question\UseCase;

use App\Question\Factory\QuestionEntityFactoryInterface;
use App\Shared\Transfer\QuestionAddTransfer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class QuestionAddUseCase implements QuestionAddUseCaseInterface
{
    public function __construct(
        private QuestionEntityFactoryInterface $questionEntityFactory,
        private ValidatorInterface             $validator,
        private EntityManagerInterface         $entityManager
    )
    {
    }

    public function createQuestion(QuestionAddTransfer $questionAddTransfer): void
    {
        $constraintViolationList = $this->validator->validate($questionAddTransfer);
        if ($constraintViolationList->count()) {
            throw new ValidationFailedException($questionAddTransfer, $constraintViolationList);
        }

        $questionEntity = $this->questionEntityFactory->create($questionAddTransfer);
        $this->entityManager->persist($questionEntity);
        $this->entityManager->flush();
    }
}
