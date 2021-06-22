<?php

namespace App\Models;

use App\Http\Traits\ArticleGetSettingTrait;
use App\Http\Traits\BelongToCategoryTrait;
use App\Http\Traits\EditedByTrait;
use App\Http\Traits\GetImageTrait;
use App\Http\Traits\GetPathTrait;
use App\Scopes\PublishedScope;
use Event;
use File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Request;
use Spatie\SchemaOrg\Schema;

class Article extends BaseModel
{
    use \Conner\Tagging\Taggable,
        ArticleGetSettingTrait,
        GetImageTrait,
        GetPathTrait,
        EditedByTrait,
        BelongToCategoryTrait;
    /**
     * Fillable field
     *
     * @var mixed
     */

    protected $fillable = [
        'title',
        'title_alias',
        'summary',
        'content',
        'image',
        'public',
        'featured',
        'params',
        'category_id',
        'hits',
        'created_by'
    ];

    protected static function boot()
    {
        parent::boot();
        if (Request::segment(1) == 'admin') {
            Event::listen('article.editing', function ($article) {
                $article->hash_tags = implode(',', $article->tagNames());
            });
            // Add global scrope created_at
            static::addGlobalScope('created_at', function (Builder $builder) {
                $builder->orderBy('created_at', 'desc');
            });
            static::creating(function ($article) {
                $article->created_by = Auth::user()->id;
                $article->updated_by = Auth::user()->id;
            });

            static::updating(function ($article) {
                $article->updated_by = Auth::user()->id;
            });

            static::deleting(function ($article) {
                if ($article->image)
                    File::delete('upload/articles/' . $article->image);
                if ($article->tagNames())
                    $article->untag();
            });
        } else {
            static::addGlobalScope(new PublishedScope);
        }
    }

    /**
     * Article::getParamsAttribute()
     *
     * @param mixed $value
     * @return object
     */
    public function getParamsAttribute($value)
    {
        return json_decode($value, false);
    }

    /**
     * Article::setParamsAttribute()
     *
     * @param mixed $value
     * @return void
     */
    public function setParamsAttribute($value)
    {
        $this->attributes['params'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Article::getFeaturedAttribute()
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

    public function renderSchema()
    {
        $render = '';
        $render .= Schema::newsArticle()
            ->url(route('paths.parse', $this->getPathAlias()))
            ->headline($this->getOriginal('title'))
            ->dateModified($this->updated_at)
            ->datePublished($this->created_at)
            ->author($this->updatedBy->display_name)
            ->wordCount(strlen($this->content))
            ->articleBody($this->summary)
            ->publisher([
                '@type' => 'Organization',
                'name' => $this->updatedBy->display_name,
                'logo' => [
                    '@type' => 'imageObject',
                    'url' => asset('upload/users/' . $this->updatedBy->avatar)
                ]
            ])
            ->image([
                '@type' => 'imageObject',
                'url' => route('image.manipulate', [$this->getSetting('thumbnail_width'), $this->getSetting('thumbnail_height'), $this->getImagePath()]),
                'width' => $this->getSetting('thumbnail_width'),
                'height' => $this->getSetting('thumbnail_height')
            ])
            ->mainEntityOfPage(true)
            ->dateline('Ho Chi Minh, Viet Nam')
            ->toScript();
        return $render;
    }

    public function getFullImage()
    {
        if ($this->image) {
            $imagePath  = 'upload/'.$this->getImageFolder() . '/' . $this->id . '/' . $this->image;
            if (\File::exists($imagePath)){
                return 'upload/'.$this->getImageFolder() . '/' . $this->id . '/' . $this->image;
            }
        }
    }
}
