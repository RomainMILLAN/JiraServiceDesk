<?php

namespace App\Subscriber;

use App\Entity\User;
use App\Enum\User\Locale;
use App\Enum\User\Theme;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class UserLocaleSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        $this->requestStack->getSession()
            ->set('_locale', Locale::FR->value)
        ;

        $this->requestStack->getSession()
            ->set('_theme', Theme::DARK->value)
        ;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }
}
