<?php

namespace App\Models;

use App\Http\Traits\EditedByTrait;
use App\Scopes\PublishedScope;

class RangeAcreage extends BaseModel
{
    use EditedByTrait;
    protected $fillable = [
        'title',
        'min',
        'max',
        'params',
        'created_by',
        'updated_by'
    ];

    protected static function boot()
    {
        parent::boot();
        if (\Request::segment(1) == 'admin') {
            static::creating(function ($RangePrice) {
                $RangePrice->created_by = \Auth::user()->id;
                $RangePrice->updated_by = \Auth::user()->id;
            });

            static::updating(function ($RangePrice) {
                $RangePrice->updated_by = \Auth::user()->id;
            });

            static::deleting(function ($estate_area) {

            });
        } else {
            static::addGlobalScope(new PublishedScope);
        }
    }

    /**
     * /**
     * RangePrice::getList()
     *
     * @param array $prependList
     * @param array $appendList
     * @return array
     */
    public static function getList($prependList = [], $appendList = [])
    {
        $all = self::select('id', 'title')->get()->toArray();
        $list = array_column($all, 'title', 'id');
        foreach ($list as $key => $title)
            $prependList[$key] = $title;
        foreach ($appendList as $key => $title)
            $prependList[$key] = $title;
        return $prependList;
    }

    /**
     * RangePrice::getParamsAttribute()
     *
     * @param mixed $value
     * @return object
     */
    public function getParamsAttribute($value)
    {
        return json_decode($value, false);
    }

    /**
     * RangePrice::setParamsAttribute()
     *
     * @param mixed $value
     * @return void
     */
    public function setParamsAttribute($value)
    {
        $this->attributes['params'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
