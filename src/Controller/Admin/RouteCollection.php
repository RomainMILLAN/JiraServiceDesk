<?php

namespace App\Controller\Admin;

use App\Controller\Contracts\RouteCollectionInterface;
use App\Controller\Traits\AdminRouteCollectionTrait;
use App\Controller\Traits\AppRouteCollectionTrait;

enum RouteCollection: string implements RouteCollectionInterface
{
    use AdminRouteCollectionTrait;

    case DASHBOARD = 'dashboard';
}
