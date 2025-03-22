<?php

namespace App\Message\Command\User\Handler;

use App\Entity\User;
use App\Message\Command\User\CreateUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
readonly class CreateUserHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(CreateUser $command): User
    {
        $user = new User(
            email: $command->email,
            firstName: $command->firstName,
            lastName: $command->lastName
        );
        $user->setRoles($command->roles);
        $user->preferredLocale = $command->preferedLocale;
        $user->preferredTheme = $command->preferedTheme;

        $password = $this->passwordHasher->hashPassword($user, $command->plainPassword);
        $user->setPassword($password);

        $this->entityManager->persist($user);

        return $user;
    }
}
