<?php

namespace App\Models;

class Area extends BaseModel
{
    protected $fillable = [
        'value',
        'public',
        'created_by',
        'updated_by'
    ];

    protected static function boot()
    {
        parent::boot();
        if (\Request::segment(1) == 'admin') {
            static::creating(function ($area) {
                $area->created_by = \Auth::user()->id;
                $area->updated_by = \Auth::user()->id;
            });
            static::updating(function ($area) {
                $area->updated_by = \Auth::user()->id;
            });
            static::deleting(function ($estate) {

            });
        }
    }

    /**
     *
     * @param array $prependList
     * @param array $appendList
     * @return array
     */
    public static function getList($prependList = [], $appendList = [])
    {
        $all = self::select('id', 'value')->get()->toArray();
        $list = array_column($all, 'value', 'id');
        foreach ($list as $key => $title)
            $prependList[$key] = $title;
        foreach ($appendList as $key => $title)
            $prependList[$key] = $title;
        return $prependList;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function estates()
    {
        return $this->belongsToMany(Estate::class, 'estate_area', 'area_id', 'estate_id');
    }
}
