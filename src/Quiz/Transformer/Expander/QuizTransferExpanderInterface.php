<?php

declare(strict_types=1);


namespace App\Quiz\Transformer\Expander;

use App\Shared\Transfer\QuizTransfer;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: 'quiz_transfer_expander')]
interface QuizTransferExpanderInterface
{
    public function expand(QuizTransfer $quizTransfer, iterable $quizContentData): void;
}
