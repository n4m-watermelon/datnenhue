<?php

namespace App\Models;

use App\Http\Traits\BelongToCategoryTrait;
use App\Http\Traits\EditedByTrait;
use App\Http\Traits\GetImageTrait;
use App\Http\Traits\GetPathTrait;
use Illuminate\Support\Facades\Auth;

class Slider extends BaseModel
{
    use GetImageTrait, BelongToCategoryTrait, GetPathTrait, EditedByTrait;
    /**
     * Fillablbe
     *
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'group_id',
        'ordering',
        'public',
        'params',
        'created_by',
        'updated_by'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($slider) {
            $slider->created_by = Auth::user()->id;
            $slider->updated_by = Auth::user()->id;
        });
        static::updating(function ($slider) {
            $slider->updated_by = Auth::user()->id;
        });
        static::deleting(function ($slider) {
            static::deleting(function ($slider) {
                File::delete('upload/' . $slider->getImageFolder() . '/' . $slider->image);
            });
        });
    }

    /**
     * Slider::getParamsAttribute()
     *
     * @param $params
     * @return mixed
     */
    public function getParamsAttribute($params)
    {
        $params_decode = json_decode($params, false);
        if (isset($params_decode->readmore)) {
            $params_decode->readmore = str_replace('{SITEURL}', route('home.index'), $params_decode->readmore);
        }
        return $params_decode;
    }

    /**
     * Slider::setParamsAttribute()
     *
     * @param mixed $params
     * @return void
     */
    public function setParamsAttribute($params)
    {
        $params['readmore'] = str_replace(route('home.index'), '{SITEURL}', $params['readmore']);
        $this->attributes['params'] = json_encode($params, JSON_UNESCAPED_UNICODE);
    }

    /**
     *
     * Slider::getOrderingAttribute()
     *
     * @param $ordering
     * @return null
     */
    public function getOrderingAttribute($ordering)
    {
        if ($ordering == 9999) {
            $ordering = null;
        }
        return $ordering;
    }

    /**
     * Slider::setOrderingAttribute()
     *
     * @param mixed $ordering
     * @return void
     */
    public function setOrderingAttribute($ordering)
    {
        if (empty($ordering)) {
            $ordering = 9999;
        }
        $this->attributes['ordering'] = $ordering;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(SliderGroup::class, 'group_id');
    }
}
