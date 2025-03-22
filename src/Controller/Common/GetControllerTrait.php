<?php

namespace App\Controller\Common;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait GetControllerTrait
{
    use HandleTrait;

    /**
     * @template T
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    protected function getByIdOrThrowNotFound(int $id, string $class): object
    {
        $entity = $this->handle(new GetEntityById($id, $class));
        if ($entity === null) {
            throw new NotFoundHttpException();
        }

        return $entity;
    }

    #[Required]
    public function setMessageBus(MessageBusInterface $queryBus): void
    {
        $this->messageBus = $queryBus;
    }
}
