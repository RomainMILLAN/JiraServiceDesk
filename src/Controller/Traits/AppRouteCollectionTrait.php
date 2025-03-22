<?php

declare(strict_types=1);

namespace App\Controller\Traits;

trait AppRouteCollectionTrait
{
    public function prefixed(): string
    {
        return 'app_' . $this->value;
    }
}
