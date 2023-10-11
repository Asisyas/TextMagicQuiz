<?php

declare(strict_types=1);


namespace App\Quiz;

use App\Quiz\UseCase\QuizFindByUserAndQuizIdUseCaseInterface;
use App\Quiz\UseCase\QuizFindCurrentUseCaseInterface;
use App\Quiz\UseCase\QuizInitUseCaseInterface;
use App\Quiz\UseCase\QuizQuestionAnswerUseCaseInterface;

interface QuizFacadeInterfaceAndQuizIdBy extends
    QuizInitUseCaseInterface,
    QuizFindCurrentUseCaseInterface,
    QuizQuestionAnswerUseCaseInterface,
    QuizFindByUserAndQuizIdUseCaseInterface
{
}
