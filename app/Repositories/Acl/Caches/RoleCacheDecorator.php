<?php

namespace App\Repositories\Acl\Caches;


use App\Repositories\Acl\Interfaces\RoleInterface;
use App\Supports\Repositories\Caches\CacheAbstractDecorator;

class RoleCacheDecorator extends CacheAbstractDecorator implements RoleInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSlug($name, $id)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    public function getList(array $prependList = [], array $appendList = [])
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
