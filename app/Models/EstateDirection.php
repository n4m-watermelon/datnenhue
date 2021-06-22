<?php

namespace App\Models;

class EstateDirection extends BaseModel
{
    //

    protected $fillable = [
        'title'
    ];

    public $timestamps = false;

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
}
