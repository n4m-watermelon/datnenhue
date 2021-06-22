<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaSeo extends Model
{
    protected $fillable = [
        'district_id',
        'category_id',
        'params'
    ];

    public function getParamsAttribute($value)
    {
        return json_decode($value, false);
    }

    
    public function setParamsAttribute($value)
    {
        $this->attributes['params'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
