<?php

namespace App\Repositories\Unit\Interfaces;

use App\Supports\Repositories\Interfaces\RepositoryInterface;

interface EstateUnitInterface extends RepositoryInterface
{
    public function getByType($type);
}
