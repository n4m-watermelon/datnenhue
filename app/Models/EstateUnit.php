<?php

namespace App\Models;

class EstateUnit extends BaseModel
{
    //
    protected $fillable = [
        'title', 'type'
    ];

    public $timestamps = false;

    public static function getList($prependList = [], $appendList = [], $where = null)
    {
        if ($where == null) {
            $all = self::select('id', 'title')->get()->toArray();
        } else {
            $all = self::where('type', $where)->select('id', 'title')->get()->toArray();
        }
        $list = array_column($all, 'title', 'id');
        foreach ($list as $key => $title)
            $prependList[$key] = $title;
        foreach ($appendList as $key => $title)
            $prependList[$key] = $title;
        return $prependList;
    }
}
