<?php

namespace App\Controller\App;

use App\Enum\User\Theme;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ThemeController extends AbstractController
{
    #[Route(
        path: '/theme/dark',
        name: RouteCollection::THEME_DARK->value,
        methods: [Request::METHOD_GET],
    )]
    public function switchToDark(
        RequestStack $requestStack,
        Request $request,
    ): Response {
        $requestStack->getSession()
            ->set('_theme', Theme::DARK->value)
        ;

        return $this->redirect(
            $request->headers->get('referer'),
        );
    }

    #[Route(
        path: '/theme/light',
        name: RouteCollection::THEME_LIGHT->value,
        methods: [Request::METHOD_GET],
    )]
    public function switchToLight(
        RequestStack $requestStack,
        Request $request,
    ): Response {
        $requestStack->getSession()
            ->set('_theme', Theme::LIGHT->value)
        ;

        return $this->redirect(
            $request->headers->get('referer'),
        );
    }
}
