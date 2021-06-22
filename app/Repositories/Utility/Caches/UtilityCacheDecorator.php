<?php

namespace App\Repositories\Utility\Caches;

use App\Repositories\Utility\Interfaces\UtilityInterface;
use App\Supports\Repositories\Caches\CacheAbstractDecorator;

class UtilityCacheDecorator extends CacheAbstractDecorator implements UtilityInterface
{
    public function getList(array $prependList = [], array $appendList = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getByType($type)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getListing($type = 2)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getByTitleAlias($title_alias)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
