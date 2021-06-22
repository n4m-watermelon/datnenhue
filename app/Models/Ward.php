<?php

namespace App\Models;

class Ward extends BaseModel
{
    protected $fillable = [
        'name', 'pre', 'district_id'
    ];

    /**
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

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function estates()
    {
        return $this->hasMany(Estate::class);
    }
}
