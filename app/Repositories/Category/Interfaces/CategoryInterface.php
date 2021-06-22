<?php

namespace App\Repositories\Category\Interfaces;

use App\Supports\Repositories\Interfaces\RepositoryInterface;

interface CategoryInterface extends RepositoryInterface
{
    public function getChildrenByRoot($root);

    public function getSingleCategory($id);

    public function getByTitleAlias($title_alias);
}