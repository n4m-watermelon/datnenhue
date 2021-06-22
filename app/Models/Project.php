<?php

namespace App\Models;

use App\Http\Traits\BelongToCategoryTrait;
use App\Http\Traits\EditedByTrait;
use App\Http\Traits\GetImageTrait;
use App\Http\Traits\GetItemPathTrait;
use App\Http\Traits\ProjectGetSettingTrait;
use App\Scopes\PublishedScope;
use File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Request;


class Project extends BaseModel
{
    use \Conner\Tagging\Taggable,
        GetImageTrait,
        GetItemPathTrait,
        EditedByTrait,
        BelongToCategoryTrait,
        ProjectGetSettingTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'category_id',
        'title',
        'title_alias',
        'summary',
        'content',
        'image',
        'public',
        'featured',
        'params',
        'hits',
        'created_by'
    ];

    /**
     *
     */
    public static function boot()
    {
        parent::boot();
        if (Request::segment(1) == 'admin') {
            // Add global scrope created_at
            static::addGlobalScope('created_at', function (Builder $builder) {
                $builder->orderBy('created_at', 'desc');
            });
            static::creating(function ($project) {
                $project->created_by = Auth::user()->id;
                $project->updated_by = Auth::user()->id;
            });
            static::updating(function ($project) {
                $project->updated_by = Auth::user()->id;
            });
            static::deleting(function ($project) {
                if ($project->image)
                    File::delete('upload/' . $project->getImageFolder() . '/' . $project->image);
                if ($project->tagNames())
                    $project->untag();
            });
        } else {
            static::addGlobalScope(new PublishedScope);
        }
    }

    /**
     * Project::getParamsAttribute()
     *
     * @param mixed $value
     * @return object
     */
    public function getParamsAttribute($value)
    {
        return json_decode($value, false);
    }

    /**
     * Project::setParamsAttribute()
     *
     * @param mixed $value
     * @return void
     */
    public function setParamsAttribute($value)
    {
        $this->attributes['params'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Project::getFeaturedAttribute()
     *
     * @param $value
     * @return int
     */
    public function getFeaturedAttribute($value)
    {
        if (is_null($value)) {
            $value = 0;
        }
        return $value;
    }
}
