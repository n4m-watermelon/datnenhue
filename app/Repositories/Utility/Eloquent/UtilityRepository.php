<?php

namespace App\Repositories\Utility\Eloquent;


use App\Repositories\Utility\Interfaces\UtilityInterface;
use App\Supports\Repositories\Eloquent\RepositoriesAbstract;

class UtilityRepository extends RepositoriesAbstract implements UtilityInterface
{
    /**
     * @param array $prependList
     * @param array $appendList
     * @return array
     */
    public function getList(array $prependList = [], array $appendList = [])
    {
        $all = $this->applyBeforeExecuteQuery($this->model->select('id', 'title'))->where('public', 1)->get()->toArray();
        $list = array_column($all, 'title', 'id');
        foreach ($list as $key => $title)
            $prependList[$key] = $title;
        foreach ($appendList as $key => $title)
            $prependList[$key] = $title;
        return $prependList;
    }

    /**
     * GetByType
     * @param $type
     * @return \Illuminate\Support\Collection
     */
    public function getByType($type)
    {
        $query = $this->model->select('id', 'title')->where('type', $type);
        return $this->applyBeforeExecuteQuery($query)->get();
    }

    public function getListing($type = 2)
    {
        $query = $this->model->where('type', $type);
        return $this->applyBeforeExecuteQuery($query)->get();
    }

    public function getByTitleAlias($title_alias)
    {
        $query = $this->model->where([
            'utilities.title_alias' => $title_alias,
            'utilities.public' => 1
        ]);
        return $this->applyBeforeExecuteQuery($query)->first();
    }
}
