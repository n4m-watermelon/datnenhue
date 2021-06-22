<?php

namespace App\Repositories\Article\Interfaces;

use App\Supports\Repositories\Interfaces\RepositoryInterface;

interface ArticleInterface extends RepositoryInterface
{
    /**
     * @return mixed
     */
    public function getImageFolder();

    /**
     * @return mixed
     */
    public function getImagePath();

    /**
     * @param int $limit
     * @return mixed
     */
    public function getFeatured($limit = 5);

    /**
     * @param $id
     * @param string $size
     * @return mixed
     */
    public function getImageObject($id, $size);
}
