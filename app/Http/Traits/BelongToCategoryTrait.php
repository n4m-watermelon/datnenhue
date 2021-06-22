<?php

namespace App\Http\Traits;

use App\Models\Category;

/**
 * ArticleGetSettingTrait
 *
 * @package Luni CMS
 * @author Jackie Do <anhvudo@gmail.com>
 * @copyright 2015
 * @version $Id$
 * @access public
 */
trait BelongToCategoryTrait
{

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
