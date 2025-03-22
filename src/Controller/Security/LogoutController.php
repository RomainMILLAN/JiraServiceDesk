<?php

namespace App\Controller\Security;

use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/security/logout',
    name: RouteCollection::LOGOUT->value,
)]
class LogoutController
{
    public function __invoke(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }
}
