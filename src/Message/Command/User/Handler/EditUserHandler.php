<?php

namespace App\Message\Command\User\Handler;

use App\Entity\User;
use App\Message\Command\User\EditUser;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
readonly class EditUserHandler
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(EditUser $command): User
    {
        $user = $command->user;

        $user->firstName = $command->firstName;
        $user->lastName = $command->lastName;
        $user->setRoles($command->roles);
        $user->preferredLocale = $command->preferedLocale;
        $user->preferredTheme = $command->preferedTheme;

        if ($command->plainPassword !== null) {
            $password = $this->passwordHasher->hashPassword($user, $command->plainPassword);
            $user->setPassword($password);
        }

        return $user;
    }
}
