<?php

namespace App\Models;

use App\Http\Traits\EditedByTrait;
use App\Http\Traits\GetImageTrait;
use App\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Request;

class Box extends BaseModel
{
    use EditedByTrait, GetImageTrait;
    //
    protected $fillable = [
        'title',
        'image',
        'public',
        'min_acreage',
        'max_acreage',
        'min_price',
        'max_price',
        'updated_by',
        'created_by'
    ];

    protected static function boot()
    {
        parent::boot();
        if (Request::segment(1) == 'admin') {
            // Add global scrope created_at
            static::addGlobalScope('created_at', function (Builder $builder) {
                $builder->orderBy('created_at', 'desc');
            });
            static::creating(function ($box) {
                $box->created_by = Auth::user()->id;
                $box->updated_by = Auth::user()->id;
            });
            static::updating(function ($box) {
                $box->updated_by = Auth::user()->id;
            });
            static::updated(function ($box) {

            });
            static::deleting(function ($box) {
                if ($box->image && \File::exists('upload/' . $this->box->getImageFolder() . '/' . $box->image))
                    \File::delete('upload/' . $this->box->getImageFolder() . '/' . $box->image);
            });
        } else {
            static::addGlobalScope(new PublishedScope);
        }
    }

    /**
     * Box::getList()
     *
     * @param array $prependList
     * @param array $appendList
     * @return array
     */
    public static function getList($prependList = [], $appendList = [])
    {
        $all = self::select('id', 'title')->get()->toArray();
        $list = array_column($all, 'title', 'id');
        foreach ($list as $key => $title)
            $prependList[$key] = $title;
        foreach ($appendList as $key => $title)
            $prependList[$key] = $title;
        return $prependList;
    }
}
