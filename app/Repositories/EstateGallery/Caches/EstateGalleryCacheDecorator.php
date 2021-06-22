<?php

namespace App\Repositories\EstateGallery\Caches;

use App\Repositories\EstateGallery\Interfaces\EstateGalleryInterface;
use App\Supports\Repositories\Caches\CacheAbstractDecorator;

class EstateGalleryCacheDecorator extends CacheAbstractDecorator implements EstateGalleryInterface
{
    public function getImageById($product_id, $id, $size)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());

    }
}
