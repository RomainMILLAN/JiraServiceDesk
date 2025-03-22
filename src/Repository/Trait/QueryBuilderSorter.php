<?php

namespace App\Repository\Trait;

use App\Model\SortParams;
use Doctrine\ORM\QueryBuilder;

trait QueryBuilderSorter
{
    protected function sort(QueryBuilder $qb, SortParams $sort, string $alias): void
    {
        $sortingChain = explode('.', $sort->by);
        $sortingField = array_pop($sortingChain);
        $previousField = $alias;
        foreach ($sortingChain as $field) {
            $qb->leftJoin(sprintf('%s.%s', $previousField, $field), $field);
            $previousField = $field;
        }

        $sortingField = str_replace(':', '.', $sortingField);

        $qb->orderBy(sprintf('%s.%s', $previousField, $sortingField), $sort->dir);
    }
}
