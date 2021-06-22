<?php

namespace App\Http\Traits;

trait EditedByTrait
{

    /**
     * createdBy()
     *
     * @return
     */
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * updatedBy()
     *
     * @return
     */
    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

}
