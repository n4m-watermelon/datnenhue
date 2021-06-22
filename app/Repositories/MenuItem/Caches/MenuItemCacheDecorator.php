<?php

namespace App\Repositories\MenuItem\Caches;

use App\Repositories\MenuItem\Interfaces\MenuItemInterface;
use App\Supports\Repositories\Caches\CacheAbstractDecorator;

class MenuItemCacheDecorator extends CacheAbstractDecorator implements MenuItemInterface
{

}