<?php

namespace App\Repositories\Setting\Caches;

use App\Repositories\Setting\Interfaces\SettingInterface;
use App\Supports\Repositories\Caches\CacheAbstractDecorator;

class SettingCacheDecorator extends CacheAbstractDecorator implements SettingInterface
{
    public function getSettingByName(String $name)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
