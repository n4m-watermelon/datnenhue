<?php

namespace App\Repositories\District\Caches;

use App\Repositories\District\Interfaces\DistrictInterface;
use App\Supports\Repositories\Caches\CacheAbstractDecorator;

class DistrictCacheDecorator extends CacheAbstractDecorator implements DistrictInterface
{
    public function getByProvinceId($province_id)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getBySlugAndId($slug, $id)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}