<?php

namespace App\Message\Query\Handler;

use App\Message\Query\GetEntityById;
use Doctrine\ORM\EntityManagerInterface;

class GetEntityByIdHandler
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    public function __invoke(GetEntityById $query)
    {
        return $this->em->find($query->class, $query->id);
    }
}
