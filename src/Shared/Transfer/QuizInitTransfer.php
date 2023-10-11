<?php

declare(strict_types=1);

namespace App\Shared\Transfer;

use Symfony\Component\Validator\Constraints as Assert;


final readonly class QuizInitTransfer
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 4, max: 30)]
        public string $userId
    )
    {
    }
}
