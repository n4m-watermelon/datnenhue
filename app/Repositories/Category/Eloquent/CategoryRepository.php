<?php

namespace App\Repositories\Category\Eloquent;

use App\Repositories\Category\Interfaces\CategoryInterface;
use App\Supports\Repositories\Eloquent\RepositoriesAbstract;

class CategoryRepository extends RepositoriesAbstract implements CategoryInterface
{
    public function getChildrenByRoot($root)
    {
        $public_categories = [];
        $query = $this->model->where('public', 1)->where('id', $root);
        $category = $this->applyBeforeExecuteQuery($query)->first();
        if ($category) {
            $descendantsAndSelf = $category->getDescendantsAndSelf();
            foreach ($descendantsAndSelf as $cate) {
                if ($cate->public == 1 && $cate->ancestors()->where('public', '!=', 1)->count() == 0) {
                    $public_categories[] = $cate->id;
                }
            }
            return $public_categories;
        }
        return null;
    }

    public function getSingleCategory($id)
    {
        $query = $this->model->select('title', 'title_alias')->where('id', $id);
        return $this->applyBeforeExecuteQuery($query)->first();
    }

    public function getByTitleAlias($title_alias)
    {
        $query = $this->model->where([
            'categories.title_alias' => $title_alias,
            'categories.public' => 1
        ]);
        return $this->applyBeforeExecuteQuery($query)->first();
    }
}