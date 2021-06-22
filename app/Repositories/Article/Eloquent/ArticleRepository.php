<?php

namespace App\Repositories\Article\Eloquent;

use App\Repositories\Article\Interfaces\ArticleInterface;
use App\Supports\Repositories\Eloquent\RepositoriesAbstract;

class ArticleRepository extends RepositoriesAbstract implements ArticleInterface
{
    public function getImageFolder()
    {
        return $this->model->getModel()->getImageFolder();
    }

    public function getImagePath()
    {
        return $this->model->getModel()->getImagePath();
    }

    /**
     * {@inheritdoc}
     */
    public function getFeatured($limit = 5)
    {
        $data = $this->model
            ->where([
                'articles.public' => 1,
                'articles.featured' => 1,
            ])
            ->limit($limit)
            ->orderBy('articles.created_at', 'desc');

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * @param $id
     * @param string $size
     * @return mixed|string
     */
    public function getImageObject($id, $size)
    {
        $product = $this->findById($id);
        $sizes = explode('x', config('media.sizes.' . $size));

        if ($product && count($sizes) > 0) {
            if ($product->image) {
                return route('image.manipulate', [ $sizes[0], $sizes[1], $product->getImagePath()]);
            }
        }
        return asset('templates/frontend/assets/img/detail_tour/1.png');
    }
}
