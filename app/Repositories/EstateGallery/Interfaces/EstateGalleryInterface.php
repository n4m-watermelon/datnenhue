<?php

namespace App\Repositories\EstateGallery\Interfaces;

use App\Supports\Repositories\Interfaces\RepositoryInterface;

interface EstateGalleryInterface extends RepositoryInterface
{
    public function getImageById($product_id, $id,$size);
}
