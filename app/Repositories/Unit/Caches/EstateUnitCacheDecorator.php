<?php

namespace App\Repositories\Unit\Caches;

use App\Repositories\Unit\Interfaces\EstateUnitInterface;
use App\Supports\Repositories\Caches\CacheAbstractDecorator;

class EstateUnitCacheDecorator extends CacheAbstractDecorator implements EstateUnitInterface
{
    public function getByType($type)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
