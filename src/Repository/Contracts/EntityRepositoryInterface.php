<?php

namespace App\Repository\Contracts;

use App\Model\SortParams;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;

interface EntityRepositoryInterface
{
    public function getListQueryBuilder(?SortParams $sort = null): QueryBuilder;

    public function applyCriteria(QueryBuilder $qb, ?FormInterface $form = null): void;
}
