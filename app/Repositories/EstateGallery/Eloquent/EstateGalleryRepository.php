<?php

namespace App\Repositories\EstateGallery\Eloquent;

use App\Repositories\EstateGallery\Interfaces\EstateGalleryInterface;
use App\Supports\Repositories\Eloquent\RepositoriesAbstract;

class EstateGalleryRepository extends RepositoriesAbstract implements EstateGalleryInterface
{
    public function getImageById($product_id, $id, $size)
    {
        $data = $this->model->where([
            'estate_galleries.estate_id' => $product_id,
            'estate_galleries.id' => $id
        ]);
        $product = $this->applyBeforeExecuteQuery($data)->first();
        $sizes = explode('x', config('media.sizes.' . $size));

        if ($product && count($sizes) > 0) {
            if ($product->image) {
                return route('image.manipulate', [$sizes[0], $sizes[1], 'estate-galleries/' . $product->name]);
            }
        }
        return asset('templates/frontend/assets/img/detail_tour/1.png');
        // TODO: Implement getImageById() method.
    }
}
