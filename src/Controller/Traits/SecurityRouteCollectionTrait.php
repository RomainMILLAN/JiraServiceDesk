<?php

declare(strict_types=1);

namespace App\Controller\Traits;

trait SecurityRouteCollectionTrait
{
    public function prefixed(): string
    {
        return 'security_' . $this->value;
    }
}
