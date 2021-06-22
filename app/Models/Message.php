<?php

namespace App\Models;

use App\Http\Traits\GetEditedAtTrait;

class Message extends BaseModel
{
    use GetEditedAtTrait;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'content'
    ];
    
}
