<?php

declare(strict_types=1);


namespace App\Quiz\Factory\Expander\Entity;

use App\Entity\Quiz;
use App\Shared\Transfer\QuizInitTransfer;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: 'quiz_entity_expander')]
interface QuizEntityExpanderInterface
{
    public function expand(Quiz $quiz, QuizInitTransfer $quizInitTransfer): void;
}
