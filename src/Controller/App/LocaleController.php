<?php

namespace App\Controller\App;

use App\Enum\User\Locale;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LocaleController extends AbstractController
{
    #[Route(
        path: '/locale/en',
        name: RouteCollection::LOCALE_EN->value,
        methods: [Request::METHOD_GET],
    )]
    public function switchToEnglish(
        RequestStack $requestStack,
        Request $request,
    ): Response {
        $requestStack->getSession()
            ->set('_locale', Locale::EN->value)
        ;

        return $this->redirect(
            $request->headers->get('referer'),
        );
    }

    #[Route(
        path: '/locale/fr',
        name: RouteCollection::LOCALE_FR->value,
        methods: [Request::METHOD_GET],
    )]
    public function switchToFrench(
        RequestStack $requestStack,
        Request $request,
    ): Response {
        $requestStack->getSession()
            ->set('_locale', Locale::FR->value)
        ;

        return $this->redirect(
            $request->headers->get('referer'),
        );
    }
}
