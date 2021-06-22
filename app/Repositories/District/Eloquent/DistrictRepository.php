<?php

namespace App\Repositories\District\Eloquent;

use App\Repositories\District\Interfaces\DistrictInterface;
use App\Supports\Repositories\Eloquent\RepositoriesAbstract;

class DistrictRepository extends RepositoriesAbstract implements DistrictInterface
{
    public function getByProvinceId($province_id)
    {
        $query = $this->model->where('province_id', $province_id)->orderBy('id', 'asc');
        return $this->applyBeforeExecuteQuery($query)->get();
    }

    public function getBySlugAndId($slug, $id)
    {
        $query = $this->model->where([
            'districts.slug_name' => $slug,
            'districts.id'        => $id
        ]);
        return $this->applyBeforeExecuteQuery($query)->first();
    }
}