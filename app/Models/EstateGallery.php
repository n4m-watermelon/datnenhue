<?php

namespace App\Models;

class EstateGallery extends BaseModel
{

    protected $fillable = [
        'name',
        'estate_id',
        'title',
        'ordering'
    ];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estate()
    {
        return $this->belongsTo(Estate::class, 'estate_id');
    }
}
