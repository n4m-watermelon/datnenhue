<?php

namespace App\Models;

use App\Http\Traits\EditedByTrait;
use App\Http\Traits\GetImageTrait;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Partner extends Model
{
    use EditedByTrait, GetImageTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'image',
        'params',
        'public',
        'created_by',
        'updated_by'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($partner) {
            $partner->created_by = Auth::user()->getKey();
            $partner->updated_by = Auth::user()->getKey();
        });
        static::updating(function ($partner) {
            $partner->updated_by = Auth::user()->getKey();
        });

        static::deleting(function ($partner) {
            $location = 'upload/' . $partner->getImageFolder() . '/' . $partner->image;
            if (File::exists($location)) {
                File::delete($location);
            }
        });
    }

    /**
     * Partner::getParamsAttribute()
     *
     * @param mixed $value
     * @return object
     */
    public function getParamsAttribute($value)
    {
        return json_decode($value, false);
    }

    /**
     * Partner::setParamsAttribute()
     *
     * @param mixed $value
     * @return void
     */
    public function setParamsAttribute($value)
    {
        $this->attributes['params'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }


}
