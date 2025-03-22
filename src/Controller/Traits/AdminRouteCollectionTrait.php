<?php

declare(strict_types=1);

namespace App\Controller\Traits;

trait AdminRouteCollectionTrait
{
    public function prefixed(): string
    {
        return 'admin_' . $this->value;
    }
}
