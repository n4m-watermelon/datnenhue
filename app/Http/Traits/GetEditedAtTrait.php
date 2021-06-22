<?php

namespace App\Http\Traits;

use Carbon\Carbon;

trait GetEditedAtTrait
{
    public function getCreatedAtAttribute($value)
    {
        if (\Request::segment(1) == 'admin') {
            $createdAt = new Carbon($value);
            if ($createdAt->diffInDays() > 2) {
                $value = $createdAt->format('H:i d/m/Y');
            } else {
                $value = $createdAt->diffForHumans();
            }
        }
        return $value;
    }

    public function getUpdatedAtAttribute($value)
    {
        if (\Request::segment(1) == 'admin') {
            $updatedAt = new Carbon($value);
            if ($updatedAt->diffInDays() > 2) {
                $value = $updatedAt->format('d/m/Y');
            } else {
                $value = $updatedAt->diffForHumans();
            }
        }
        return $value;
    }
}
