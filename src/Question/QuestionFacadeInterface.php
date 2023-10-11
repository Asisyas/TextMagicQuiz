<?php

declare(strict_types=1);

namespace App\Question;

use App\Question\UseCase\QuestionAddUseCaseInterface;
use App\Question\UseCase\QuestionCheckAnswersUseCaseInterface;
use App\Question\UseCase\QuestionLookupUseCaseInterface;

interface QuestionFacadeInterface extends
    QuestionAddUseCaseInterface,
    QuestionLookupUseCaseInterface,
    QuestionCheckAnswersUseCaseInterface
{
}
