<?php

namespace App\Repositories\Category\Caches;

use App\Repositories\Category\Interfaces\CategoryInterface;
use App\Supports\Repositories\Caches\CacheAbstractDecorator;

class CategoryCacheDecorator extends CacheAbstractDecorator implements CategoryInterface
{
    public function getChildrenByRoot($root)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getSingleCategory($id)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getByTitleAlias($title_alias)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}