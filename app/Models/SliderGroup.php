<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

class SliderGroup extends BaseModel
{
    protected $fillable = [
        'title',
        'summary',
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
    }

    public static function getList($prependList = [], $appendList = [])
    {
        $all = self::all(['id', 'title'])->toArray();
        $list = array_column($all, 'title', 'id');
        foreach ($list as $key => $title) {
            $prependList[$key] = $title;
        }
        foreach ($appendList as $key => $title) {
            $prependList[$key] = $title;
        }
        return $prependList;
    }

    /**
     * SliderGroup::sliders()
     *
     * @return Relationship
     */
    public function sliders()
    {
        return $this->hasMany(Slider::class, 'group_id');
    }
}
