<?php

namespace App\Repositories\Setting\Eloquent;

use App\Repositories\Setting\Interfaces\SettingInterface;
use App\Supports\Repositories\Eloquent\RepositoriesAbstract;

class SettingRepository extends RepositoriesAbstract implements SettingInterface
{
    public function getSettingByName(String $name)
    {
        $data    = $this->model->where('name', '=', $name);
        $setting = $this->applyBeforeExecuteQuery($data)->first();
        if (!$setting) {
            return false;
        }
        $setting->value = (empty($setting->value)) ?
            json_decode($setting->default, false) :
            json_decode($setting->value, false);
        return $setting->value;
    }
}
