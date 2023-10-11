<?php

declare(strict_types=1);


namespace App\Question\UseCase;

use App\Entity\Question;
use Doctrine\Common\Collections\Collection;

interface QuestionLookupUseCaseInterface
{
    /**
     * @return Collection<Question>
     */
    public function lookupQuestions(int $count): Collection;
}
