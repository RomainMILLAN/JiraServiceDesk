<?php

namespace App\Repository;

use App\Model\SortParams;
use App\Repository\Contracts\EntityRepositoryInterface;
use App\Repository\Trait\QueryBuilderSorter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;

abstract class AbstractEntityRepository extends ServiceEntityRepository implements EntityRepositoryInterface
{
    use QueryBuilderSorter;

    protected const ALIAS = 'o';

    public function getListQueryBuilder(?SortParams $sort = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder(static::ALIAS);

        if ($sort !== null) {
            $this->sort($qb, $sort, static::ALIAS);
        }

        return $qb;
    }

    public function applyCriteria(QueryBuilder $qb, ?FormInterface $form = null): void
    {
    }
}
