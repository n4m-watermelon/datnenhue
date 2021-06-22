<?php

namespace App\Models;

use App\Http\Traits\GetImageTrait;
use App\Http\Traits\GetPathTrait;

class District extends BaseModel
{
    use GetPathTrait, GetImageTrait;

    protected $fillable = [
        'name',
        'image',
        'slug_name',
        'pre',
        'province_id',
        'order',
        'params',
        'summary'
    ];
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
    }

    /**
     * District::getList()
     *
     * @param array $prependList
     * @param array $appendList
     * @param null $where
     * @return array
     */
    public static function getList($prependList = [], $appendList = [], $where = null)
    {
        if ($where == null) {
            $all = self::select('id', 'name')->orderBy('order', 'asc')->get()->toArray();
        } else {
            $all = self::where('province_id', $where)->select('id', 'name')->orderBy('order', 'asc')->get()->toArray();
        }
        $list = array_column($all, 'name', 'id');
        foreach ($list as $key => $title)
            $prependList[$key] = $title;
        foreach ($appendList as $key => $title)
            $prependList[$key] = $title;
        return $prependList;
    }
    public function getFullImage()
    {
        if ($this->image) {
            $imagePath  = 'upload/'.$this->getImageFolder() . '/' . $this->image;
            if (\File::exists($imagePath)){
                return 'upload/'.$this->getImageFolder() . '/' . $this->image;
            }
        }
    }
    /**
     * @return string
     */
    public function getFullName()
    {
        return ucfirst($this->pre) . ' ' . ucfirst($this->name);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wards()
    {
        return $this->hasMany(Ward::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function streets()
    {
        return $this->hasMany(Street::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function estates()
    {
        return $this->hasMany(Estate::class);
    }

    /**
     * Article::getParamsAttribute()
     *
     * @param mixed $value
     * @return object
     */
    public function getParamsAttribute($value)
    {
        return json_decode($value, false);
    }

    /**
     * Article::setParamsAttribute()
     *
     * @param mixed $value
     * @return void
     */
    public function setParamsAttribute($value)
    {
        $this->attributes['params'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
