<?php

namespace App\Models;

class FormContact extends BaseModel
{
    //
    protected $fillable = [
        'name',
        'phone',
        'email',
        'message'
    ];
}
