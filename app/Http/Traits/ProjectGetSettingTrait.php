<?php

namespace App\Http\Traits;

use App\Models\Setting;

trait ProjectGetSettingTrait
{
    /**
     *
     * @param null $setting
     * @return mixed
     */
    public function getSetting($setting = null)
    {
        if (is_null($setting)) {
            return Setting::getSetting('projects');
        }
        return Setting::getSetting('projects')->$setting;
    }
}
