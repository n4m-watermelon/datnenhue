<?php

namespace App\Filters;

use App\Http\Traits\ProjectGetSettingTrait;
use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

/**
 * Class ImageThumbProjectFilters
 *
 * @package App\Filters
 */
class ImageThumbProjectFilter implements FilterInterface
{
    use ProjectGetSettingTrait;

    public function applyFilter(Image $image)
    {
        return $image->fit($this->getSetting('thumbnail_width'), $this->getSetting('thumbnail_height'),function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
    }
}