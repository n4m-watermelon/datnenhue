<?php

namespace App\Models;

class Utility extends BaseModel
{
    //
    protected $fillable = [
        'title',
        'title_alias',
        'public',
        'type',
        'params'
    ];


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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function estates()
    {
        return $this->belongsToMany(Estate::class, 'estate_utilities', 'utility_id', 'estate_id');
    }
}
