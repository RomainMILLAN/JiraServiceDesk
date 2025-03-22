<?php

namespace App\Controller\Admin\User;

use App\Controller\Contracts\RouteCollectionInterface;
use App\Controller\Traits\AdminRouteCollectionTrait;
use App\Controller\Traits\AppRouteCollectionTrait;

enum RouteCollection: string implements RouteCollectionInterface
{
    use AdminRouteCollectionTrait;

    case LIST = 'user_list';
    case EDIT = 'user_edit';
    case CREATE = 'user_create';
}
