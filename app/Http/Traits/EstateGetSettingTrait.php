<?php
namespace App\Http\Traits;

use App\Models\Setting;

/**
 * ShoppingGetSettingTrait
 *
 * @package WCSEO CMS
 * @author HIEU PHAM <quochieuhcm@gmail.com>
 * @copyright 2017
 * @version $Id$
 * @access public
 */
trait EstateGetSettingTrait
{
    public function getSetting($setting = null)
    {
        if (is_null($setting)) {
            return Setting::getSetting('estates');
        }
        return Setting::getSetting('estates')->$setting;
    }
}
