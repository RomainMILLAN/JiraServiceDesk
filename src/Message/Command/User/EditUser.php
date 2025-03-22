<?php

namespace App\Message\Command\User;

use App\Entity\User;

class EditUser extends AbstractUserDTO
{
    public function __construct(
        public readonly User $user,
    ) {
        parent::__construct(
            email: $this->user->email,
            lastName: $this->user->lastName,
            firstName: $this->user->firstName,
            roles: $this->user->getRoles(),
            preferedLocale: $this->user->preferredLocale,
            preferedTheme: $this->user->preferredTheme,
        );
    }
}
