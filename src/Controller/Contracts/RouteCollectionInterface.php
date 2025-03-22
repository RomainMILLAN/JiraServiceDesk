<?php

declare(strict_types=1);

namespace App\Controller\Contracts;

interface RouteCollectionInterface
{
    public function prefixed(): string;
}
