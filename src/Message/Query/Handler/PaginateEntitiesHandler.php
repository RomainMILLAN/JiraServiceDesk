<?php

namespace App\Message\Query\Handler;

use App\Message\Query\PaginateEntities;
use App\Repository\Trait\QueryBuilderSorter;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Spiriit\Bundle\FormFilterBundle\Filter\FilterBuilderUpdater;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PaginateEntitiesHandler
{
    use QueryBuilderSorter;

    private EntityManagerInterface $entityManager;

    private FilterBuilderUpdater $filterBuilderUpdater;

    public function __construct(
        EntityManagerInterface $entityManager,
        FilterBuilderUpdater $filterBuilderUpdater
    ) {
        $this->entityManager = $entityManager;
        $this->filterBuilderUpdater = $filterBuilderUpdater;
    }

    public function __invoke(PaginateEntities $query): Pagerfanta
    {
        $qb = $query->qb;
        if ($qb === null) {
            $qb = $this->entityManager->getRepository($query->class)
                ->getListQueryBuilder()
            ;
        }
        $this->sort($qb, $query->sort, 'o');

        if ($query->form !== null) {
            $this->filterBuilderUpdater->addFilterConditions($query->form, $qb);
        }

        $pagination = new Pagerfanta(
            new QueryAdapter(
                $qb,
            )
        );

        return $pagination->setCurrentPage($query->page);
    }
}
