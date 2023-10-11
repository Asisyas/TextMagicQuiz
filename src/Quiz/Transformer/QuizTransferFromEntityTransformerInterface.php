<?php

declare(strict_types=1);


namespace App\Quiz\Transformer;

use App\Entity\Quiz;
use App\Shared\Transfer\QuizTransfer;

interface QuizTransferFromEntityTransformerInterface
{
    public function transform(Quiz $quiz): QuizTransfer;
}
