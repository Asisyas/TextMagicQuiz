<?php

declare(strict_types=1);


namespace App\Question\Factory;

use App\Entity\Question;
use App\Shared\Transfer\QuestionAddTransfer;

interface QuestionEntityFactoryInterface
{
    public function create(QuestionAddTransfer $questionAddTransfer): Question;
}
