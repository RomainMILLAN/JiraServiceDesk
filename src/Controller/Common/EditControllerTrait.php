<?php

namespace App\Controller\Common;

use App\Message\Query\GetEntityById;
use App\Message\Trait\EnvelopeExtractorTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait EditControllerTrait
{
    use EnvelopeExtractorTrait;
    use HandleTrait;

    protected MessageBusInterface $queryBus;

    protected function handleQuery($query)
    {
        return $this->extractEnvelopeContent($this->queryBus->dispatch($query));
    }

    /**
     * @template T
     *
     * @psalm-param T $class
     *
     * @return T
     */
    protected function getByIdOrThrowNotFound(string $class, int $id)
    {
        $entity = $this->extractEnvelopeContent($this->queryBus->dispatch(new GetEntityById($class, $id)));

        if ($entity === null) {
            throw new NotFoundHttpException(sprintf('%s id provided does not exists', $class));
        }

        return $entity;
    }

    #[Required]
    public function setMessageBus(MessageBusInterface $commandBus): void
    {
        $this->messageBus = $commandBus;
    }

    #[Required]
    public function setQueryBus(MessageBusInterface $queryBus): void
    {
        $this->queryBus = $queryBus;
    }
}
