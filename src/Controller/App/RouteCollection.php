<?php

namespace App\Controller\App;

use App\Controller\Contracts\RouteCollectionInterface;
use App\Controller\Traits\AppRouteCollectionTrait;

enum RouteCollection: string implements RouteCollectionInterface
{
    use AppRouteCollectionTrait;

    case LOCALE_FR = 'locale_fr';
    case LOCALE_EN = 'locale_en';
    case THEME_DARK = 'theme_dark';
    case THEME_LIGHT = 'theme_light';
}
