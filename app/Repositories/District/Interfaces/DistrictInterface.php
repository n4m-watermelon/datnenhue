<?php

namespace App\Repositories\District\Interfaces;

use App\Supports\Repositories\Interfaces\RepositoryInterface;

interface DistrictInterface extends RepositoryInterface
{
    public function getByProvinceId($province_id);

    public function getBySlugAndId($slug, $id);
}