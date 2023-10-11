<?php

declare(strict_types=1);


namespace App\Question\UseCase;

use App\Shared\Transfer\QuestionAddTransfer;

interface QuestionAddUseCaseInterface
{
    public function createQuestion(QuestionAddTransfer $questionAddTransfer): void;
}
