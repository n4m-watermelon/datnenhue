<?php

namespace App\Repositories\Eloquent;

use App\Article;

/**
 * Class ArticleEloquentResponsitory
 * @package App\Repositories\Eloquent
 */
class ArticleEloquentResponsitory extends EloquentBaseReponsitory implements ArticleResponsitoryInterface
{
    /**
     * @return string
     */
    public function setModel()
    {
        return Article::class;
    }

    /**
     *
     * @return mixed
     */
    public function getImageFolder()
    {
        return $this->_model->getImageFolder();
    }
}

?>