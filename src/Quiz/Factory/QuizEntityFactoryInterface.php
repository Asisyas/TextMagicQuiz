<?php

declare(strict_types=1);


namespace App\Quiz\Factory;

use App\Entity\Quiz;
use App\Shared\Transfer\QuizInitTransfer;

interface QuizEntityFactoryInterface
{
    public function create(QuizInitTransfer $quizInitTransfer): Quiz;
}
