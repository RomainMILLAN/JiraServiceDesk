<?php

namespace App\Subscriber;

use App\Enum\User\Theme;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ThemeSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (! $request->hasPreviousSession()) {
            return;
        }

        if ($request->getSession()->get('_theme') == null) {
            $request->getSession()
                ->set('_theme', Theme::DARK->value)
            ;
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}
