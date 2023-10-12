<?php

declare(strict_types=1);

namespace App\Shared\Transfer;

use Symfony\Component\Validator\Constraints as Assert;

final class AuthenticationTransfer
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 4, max: 32)]
    public string $username;
}
