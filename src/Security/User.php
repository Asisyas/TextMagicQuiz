<?php

declare(strict_types=1);


namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

final readonly class User implements UserInterface
{
    public function __construct(
        private string $username
    )
    {
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
