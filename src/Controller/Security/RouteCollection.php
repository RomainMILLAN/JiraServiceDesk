<?php

namespace App\Controller\Security;

use App\Controller\Contracts\RouteCollectionInterface;
use App\Controller\Traits\AppRouteCollectionTrait;

enum RouteCollection: string implements RouteCollectionInterface
{
    use AppRouteCollectionTrait;

    case LOGIN = 'login';
    case LOGOUT = 'logout';
}
