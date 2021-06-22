<?php

namespace App\Http\Traits;

use App\Models\Setting;

trait ArticleGetSettingTrait
{

    public function getSetting($setting = null)
    {
        if (is_null($setting)) {
            return Setting::getSetting('articles');
        }
        return Setting::getSetting('articles')->$setting;
    }
}
