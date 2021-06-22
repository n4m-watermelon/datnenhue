<?php

namespace App\Repositories\Unit\Eloquent;

use App\Repositories\Unit\Interfaces\EstateUnitInterface;
use App\Supports\Repositories\Eloquent\RepositoriesAbstract;

class EstateEstateUnitRepositry extends RepositoriesAbstract implements EstateUnitInterface
{
    /**
     * @param $type
     * @return \Illuminate\Support\Collection
     */
    public function getByType($type)
    {
        $query = $this->model->select('id', 'title')->where('type', $type);
        return $this->applyBeforeExecuteQuery($query)->get();
    }

}
