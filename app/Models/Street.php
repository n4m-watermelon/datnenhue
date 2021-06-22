<?php

namespace App\Models;

class Street extends BaseModel
{
    protected $fillable = [
        'name', 'pre', 'street_slug', 'district_id'
    ];

    public $timestamps = false;

    /**
     * Street::getList()
     *
     * @param array $prependList
     * @param array $appendList
     * @param null $where
     * @return array
     */
    public static function getList($prependList = [], $appendList = [], $where = null)
    {
        if ($where == null) {
            $all = self::select('id', 'name')->get()->toArray();
        } else {
            $all = self::where('district_id', $where)->select('id', 'name')->get()->toArray();
        }
        $list = array_column($all, 'name', 'id');
        foreach ($list as $key => $title)
            $prependList[$key] = $title;
        foreach ($appendList as $key => $title)
            $prependList[$key] = $title;
        return $prependList;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
