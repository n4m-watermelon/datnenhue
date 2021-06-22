<?php

namespace App\Models;

use App\Http\Traits\EditedByTrait;
use App\Http\Traits\GetEditedAtTrait;
use App\Http\Traits\GetImageTrait;
use App\Http\Traits\GetPathTrait;
use App\Http\Traits\Rateable;
use Baum\Node;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\SchemaOrg\Schema;

class Category extends Node
{
    use GetPathTrait, EditedByTrait, GetImageTrait, Rateable, GetEditedAtTrait;
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * Fillable field
     *
     * @var mixed
     */
    protected $fillable = [
        'title',
        'title_alias',
        'image',
        'summary',
        'component',
        'params',
        'public',
        'type_id',
        'created_by',
        'updated_by'
    ];

    protected static function boot()
    {
        parent::boot();
        if (\Request::segment(1) == 'admin') {
            static::addGlobalScope('created_at', function (Builder $builder) {
                $builder->orderBy('created_at', 'desc');
            });
            static::creating(function ($category) {
                $category->created_by = Auth::user()->id;
                $category->updated_by = Auth::user()->id;
            });
            static::updating(function ($category) {
                $category->updated_by = Auth::user()->id;
            });
            static::deleting(function ($category) {
                if ($category->image) {
                    \File::delete('upload/categories/' . $category->image);
                }
            });
        }
    }

    /**
     * Category::getParamsAttribute()
     *
     * @param mixed $value
     * @return object
     */
    public function getParamsAttribute($value)
    {
        return json_decode($value, false);
    }

    /**
     * Category::setParamsAttribute()
     *
     * @param mixed $value
     * @return void
     */
    public function setParamsAttribute($value)
    {
        $this->attributes['params'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function getDistrictNameAttribute()
    {
        $district = null;
        if (isset($this->params->district_id)) {
            $district = District::find($this->params->district_id);
            if ($district) {
                return $district->name;
            }
        }
        return null;
    }

    /**
     * Return an key-value array indicating the node's depth with $separator
     *
     * @param string $column
     * @param string $key
     * @param string $separator
     * @param mixed $scopedFieldsValues
     * @return Array
     */
    public static function getNestedList($column, $key = null, $separator = ' ', $scopedFieldsValues = [])
    {
        $instance = new static;
        $key = $key ?: $instance->getKeyName();
        $depthColumn = $instance->getDepthColumnName();
        foreach ($scopedFieldsValues as $fieldName => $Value) {
            $instance->scoped[] = $fieldName;
            $instance->$fieldName = $Value;
        }
        $nodes = $instance->newNestedSetQuery()->get()->toArray();
        return array_combine(array_map(function ($node) use ($key) {
            return $node[$key];
        }, $nodes), array_map(function ($node) use ($separator, $depthColumn, $column) {
            return str_repeat($separator, $node[$depthColumn]) . $node[$column];
        }, $nodes));
    }

    /**
     *
     * Category::getList()
     *
     * @param array $scopedFieldsValues
     * @param array $prependList
     * @param array $appendList
     * @return array
     */
    public static function getList($scopedFieldsValues = [], $prependList = [], $appendList = [])
    {
        $nestedList = self::getNestedList('title', 'id', '|-- ', $scopedFieldsValues);
        foreach ($nestedList as $key => $title) {
            $prependList[$key] = $title;
        }
        foreach ($appendList as $key => $title) {
            $prependList[$key] = $title;
        }
        return $prependList;
    }

    /**
     *
     * Category::getPath()
     *
     * @param string $separator
     * @return string
     */
    public function getPath($separator = '/')
    {
        $categorySet = array_column($this->getAncestorsAndSelf(['title'])->toArray(), 'title');
        return implode($separator, $categorySet);
    }

    /**
     * Category::getPathAlias()
     *
     * @param string $separator
     * @return string
     */
    public function getPathAlias($separator = '/')
    {
        $categorySet = array_column($this->getAncestorsAndSelf(['title_alias'])->toArray(), 'title_alias');
        return implode($separator, $categorySet);
    }

    /**
     * Category::projects()
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * $category->estates()
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function estates()
    {
        return $this->hasMany(Estate::class);
    }

    /**
     * @return array|string
     */
    public function renderSchemaBlogPosts()
    {
        $render = null;
        if ($this->articles->count() > 0) {
            foreach ($this->articles as $item) {
                $render[] = [
                    "@type" => "blogPosting",
                    "mainEntityOfPage" => route('paths.parse', $item->getPathAlias()),
                    "headline" => $item->title,
                    "author" => $item->updatedBy->display_name,
                    "datePublished" => $item->created_at,
                    "dateModified" => $item->updated_at,
                    "image" => [
                        "@type" => "imageObject",
                        'url' => route('image.manipulate', [$item->getSetting('thumbnail_width'), $item->getSetting('thumbnail_height'), $item->getImagePath()]),
                        'width' => $item->getSetting('thumbnail_width'),
                        'height' => $item->getSetting('thumbnail_height')
                    ],
                    "publisher" => [
                        '@type' => 'Organization',
                        'name' => $item->updatedBy->display_name,
                        'logo' => [
                            '@type' => 'imageObject',
                            'url' => asset('upload/users/' . $item->updatedBy->avatar)
                        ]
                    ]
                ];
            }
        }
        return $render;
    }

    /**
     * $this->renderSchema()
     *
     * @return string
     */
    public function renderSchema()
    {
        $render = '';
        $render .= Schema::blog()
            ->url(route('categories.show', $this->getPathAlias()))
            ->name($this->title)
            ->description($this->summary ? $this->summary : $this->title)
            ->publisher([
                "@type" => "Organization",
                "name" => $this->title
            ])
            ->aggregateRating([
                '@type' => 'AggregateRating',
                '@id' => route('home.index'),
                'ratingValue' => ($this->userSumRating() > 1) ? $this->userAverageRating / $this->userSumRating() : 1,
                'bestRating' => 5,
                'ratingCount' => $this->userSumRating()
            ])
            ->blogPosts($this->renderSchemaBlogPosts())
            ->toScript();
        return $render;
    }
}
