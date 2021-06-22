<?php

namespace App\Repositories\Setting\Interfaces;

use App\Supports\Repositories\Interfaces\RepositoryInterface;

interface SettingInterface extends RepositoryInterface
{
    public function getSettingByName(String $name);
}
