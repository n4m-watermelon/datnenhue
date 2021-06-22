<?php

namespace App\Models;

class Rating extends BaseModel
{
    //
    protected $fillable = [
        'rating'
    ];

    public function rateable()
    {
        return $this->morphTo();
    }
}
