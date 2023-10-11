<?php

declare(strict_types=1);


namespace App\Question\Factory\Expander\Entity;

use App\Entity\Question;
use App\Shared\Transfer\QuestionAddTransfer;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: 'question_entity_expander')]
interface QuestionEntityExpanderInterface
{
    public function expand(Question $question, QuestionAddTransfer $questionAddTransfer): void;
}
