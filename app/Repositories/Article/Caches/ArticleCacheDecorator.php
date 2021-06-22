<?php

namespace App\Repositories\Article\Caches;

use App\Repositories\Article\Interfaces\ArticleInterface;
use App\Supports\Repositories\Caches\CacheAbstractDecorator;

class ArticleCacheDecorator extends CacheAbstractDecorator implements ArticleInterface
{
    public function getImageFolder()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getImagePath()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getFeatured($limit = 5)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getImageObject($id, $size)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}