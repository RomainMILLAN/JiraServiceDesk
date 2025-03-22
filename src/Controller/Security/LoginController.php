<?php

namespace App\Controller\Security;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(
    path: '/security/login',
    name: RouteCollection::LOGIN->value,
)]
class LoginController extends AbstractController
{
    public function __invoke(
        AuthenticationUtils $authenticationUtils,
        #[CurrentUser]
        ?User $user = null,
    ): Response {
//        if ($user !== null) {
//            return $this->redirectToRoute(\App\Controller\App\RouteCollection::DASHBOARD->prefixed());
//        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
