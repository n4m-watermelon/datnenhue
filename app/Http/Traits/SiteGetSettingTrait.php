<?php

namespace App\Http\Traits;

use App\Models\Setting;

/**
 * SiteGetSettingTrait
 *
 * @package WCSEO CMS
 * @author HIEU PHAM <quochieuhcm@gmail.com>
 * @copyright 2017
 * @version $Id$
 * @access public
 */
trait SiteGetSettingTrait
{

    public function getSiteSetting($setting = null)
    {
        if (is_null($setting)) {
            return Setting::getSetting('site');
        }
        return Setting::getSetting('site')->$setting;
    }
}
