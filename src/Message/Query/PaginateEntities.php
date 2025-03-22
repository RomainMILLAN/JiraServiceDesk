<?php

namespace App\Message\Query;

use App\Model\SortParams;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;

class PaginateEntities
{
    public SortParams $sort;

    public string $class;

    public int $page;

    public ?FormInterface $form = null;

    public ?QueryBuilder $qb = null;

    public function __construct(
        string $class,
        string $sort,
        int $page = 1,
        ?FormInterface $form = null,
        ?QueryBuilder $qb = null
    ) {
        $this->class = $class;
        $this->sort = SortParams::createSort($sort);
        $this->page = $page;
        $this->form = $form;
        $this->qb = $qb;
    }
}
