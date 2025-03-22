<?php

namespace App\Controller\Common;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait CreateControllerTrait
{
    use HandleTrait;

    #[Required]
    public function setMessageBus(MessageBusInterface $commandBus): void
    {
        $this->messageBus = $commandBus;
    }
}
