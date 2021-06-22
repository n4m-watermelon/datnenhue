<?php

namespace App\Models;

use App\Http\Traits\BelongToCategoryTrait;
use App\Http\Traits\EditedByTrait;
use App\Http\Traits\EstateGetSettingTrait;
use App\Http\Traits\GetEditedAtTrait;
use App\Http\Traits\GetImageTrait;
use App\Http\Traits\GetPathTrait;
use App\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemaOrg\Schema;

class Estate extends BaseModel
{
    use \Conner\Tagging\Taggable,
        GetImageTrait,
        GetPathTrait,
        GetEditedAtTrait,
        BelongToCategoryTrait,
        EstateGetSettingTrait,
        EditedByTrait;

    protected $fillable = [
        'lat',
        'lng',
        'image',
        'title',
        'title_alias',
        'summary',
        'content',
        'type_id',
        'category_id',
        'province_id',
        'district_id',
        'ward_id',
        'street_id',
        'address',
        'price',
        'unit_id',
        'area',
        'width',
        'land_width',
        'structure',
        'floor_numbers',
        'room_number',
        'home_direction',
        'time_for_rent',
        'time_payment',
        'time_decor',
        'interior',
        'approval',
        'hits',
        'public',
        'status',
        'featured',
        'params',
        'contact_id',
        'created_by',
        'updated_by'
    ];

    protected static function boot()
    {
        parent::boot();
        if (\Request::segment(1) == 'admin') {
            // Add global scrope created_at
            static::addGlobalScope('created_at', function (Builder $builder) {
                $builder->orderBy('created_at', 'desc');
            });
            \Event::listen('estate.editing', function ($estate) {
                $estate->hash_tag = implode(',', $estate->tagNames());
            });
            static::creating(function ($estate) {
                $estate->created_by = \Auth::user()->id;
                $estate->updated_by = \Auth::user()->id;
            });
            static::updating(function ($estate) {
                $estate->updated_by = \Auth::user()->id;
            });
            static::deleting(function ($estate) {
                if ($estate->image)
                    \File::delete('upload/' . $estate->getImageFolder() . '/' . $estate->image);
                if ($estate->images->count() != 0) {
                    foreach ($estate->images->all() as $library) {
                        \File::delete('upload/estate-galleries/' . $library->name);
                    }
                }
                $estate->areas()->detach();
                if ($estate->tagNames())
                    $estate->untag();
            });
        } else {
            static::addGlobalScope(new PublishedScope);
        }
    }

    public function getPriceAttribute($value)
    {
        return $value;
    }

    public function setPriceAttribute($value)
    {
        if ($value != null)
            $this->attributes['price'] = str_replace(',', '.', $value);
        else
            $this->attributes['price'] = '0.0000';
    }

    public function getContentAttribute($value)
    {
        return str_replace('{SITEURL}', route('home.index'), $value);
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = str_replace(route('home.index'), '{SITEURL}', $value);
    }

    public function setFeeAttribute($value)
    {
        $this->attributes['fee'] = str_replace(route('home.index'), '{SITEURL}', $value);
    }

    public function getFeeAttribute($value)
    {
        return str_replace('{SITEURL}', route('home.index'), $value);
    }

    public function setFengAttribute($value)
    {
        $this->attributes['feng'] = str_replace(route('home.index'), '{SITEURL}', $value);
    }

    public function getFengAttribute($value)
    {
        return str_replace('{SITEURL}', route('home.index'), $value);
    }

    public function setContentLocationAttribute($value)
    {
        $this->attributes['content_location'] = str_replace(route('home.index'), '{SITEURL}', $value);
    }

    public function getContentLocationAttribute($value)
    {
        return str_replace('{SITEURL}', route('home.index'), $value);
    }

    public function getDirectAttribute($value)
    {
        if (!is_null($this->direction)) {
            $value = 'Hướng ' . $this->direction->title;
        } else {
            $value = 'KXĐ';
        }
        return $value;
    }

    public function getAddressAttribute($value)
    {
        if (\Request::segment(1) != 'admin') {
            if (!is_null($this->getOriginal('address'))) $value = $this->getOriginal('address'); else $value = 'Đang cập nhật';
        }
        return $value;
    }

    public function setWidthAttribute($value)
    {
        if ($value) {
            $this->attributes['width'] = rtrim($value, ',');
        }
    }
    public function setLandWidthAttribute($value)
    {
        if ($value) {
            $this->attributes['land_width'] = rtrim($value, ',');
        }
    }

    /**
     * @param $value
     */
    public function setAreaAttribute($value)
    {
        if ($value) {
            $this->attributes['area'] = $value;
        } else {
            $this->attributes['area'] = ($this->width && $this->land_width) ? $this->width * $this->land_width : null;
        }
    }

    /**
     * Estate::getParamsAttribute()
     *
     * @param mixed $value
     * @return object
     */
    public function getParamsAttribute($value)
    {
        return json_decode($value, false);
    }

    /**
     * Estate::setParamsAttribute()
     *
     * @param mixed $value
     * @return void
     */
    public function setParamsAttribute($value)
    {
        $this->attributes['params'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit()
    {
        return $this->belongsTo(EstateUnit::class, 'unit_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function street()
    {
        return $this->belongsTo(Street::class, 'street_id');
    }


    public function direction()
    {
        return $this->belongsTo(EstateDirection::class, 'home_direction');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(EstateGallery::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_estate', 'estate_id', 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function areas()
    {
        return $this->belongsToMany(Area::class, 'estate_area', 'estate_id', 'area_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function utilities()
    {
        return $this->belongsToMany(Utility::class, 'estate_utilities', 'estate_id', 'utility_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    /**
     * @return string
     */
    public function getFullArea()
    {
        $fullArea = '';
        if ($this->width && $this->land_width) {
            $fullArea .= $this->width . 'm x ' . $this->land_width . 'm';
        }
        if ($this->floor_numbers) {
            $fullArea .= ', ' . ucfirst($this->floor_numbers);
        }
        return $fullArea;
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

    /**
     * Estate::SearchByPrice()
     *
     * @param $query
     * @param $min_price
     * @param $max_price
     * @return mixed
     */
    public function scopeSearchByPrice($query, $min_price, $max_price)
    {
        if ($max_price && $min_price) {
            $query->where(function ($query) use ($min_price, $max_price) {
                $query->whereBetween('price', [$min_price, $max_price]);
            });
        }
        return $query;
    }

    /**
     * Estate::SearchByAcreage()
     *
     * @param $query
     * @param $min_acreage
     * @param $max_acreage
     * @return mixed
     */
    public function scopeSearchByAcreage($query, $min_acreage, $max_acreage)
    {
        if ($min_acreage && $max_acreage) {
            $query->whereHas('areas', function ($query) use ($min_acreage, $max_acreage) {
                $query->whereBetween('value', [$min_acreage, $max_acreage]);
            });
        }
        return $query;
    }

    /**
     *
     *
     * @param $query
     * @param $min_price
     * @param $max_price
     * @param $min_acreage
     * @param $max_acreage
     * @return mixed
     */
    public function scopeFilterSearch($query, $min_price, $max_price, $min_acreage, $max_acreage)
    {
        if (!is_null($min_price) && !is_null($max_price)) {
            if ($max_price == 0) {
                $query->where(function ($query) use ($min_price, $max_price) {
                    $query->where('price', '>', $min_price);
                });
            } else {
                $query->where(function ($query) use ($min_price, $max_price) {
                    $query->whereBetween('price', [$min_price, $max_price]);
                });
            }
        }
        if ($min_acreage && $max_acreage) {
            if ($max_acreage == 0) {
                $query->whereHas('areas', function ($query) use ($min_acreage, $max_acreage) {
                    $query->whereBetween('value', '>', $min_acreage);
                });
            } else {
                $query->whereHas('areas', function ($query) use ($min_acreage, $max_acreage) {
                    $query->whereBetween('value', [$min_acreage, $max_acreage]);
                });
            }

        }
        return $query;
    }

    /**
     *  Scope a query to search estate by filter
     *
     * @param $query
     * @param $keyword
     * @param $district_id
     * @param $ward_id
     * @param $street_id
     * @param $filter_direction
     * @param $filter_acreage
     * @param $filter_price
     * @return mixed
     */
    public function scopeSearchByOption($query, $keyword, $district_id, $ward_id, $street_id, $filter_direction, $filter_acreage, $filter_price)
    {
        // LIKE NAME COMPARE TITLE, PARAMS
        if ($keyword != '') {
            $query->where(function ($query) use ($keyword) {
                $query->where("title", "LIKE", "%$keyword%")
                    ->orWhere("summary", "LIKE", "%$keyword%")
                    ->orWhere("content", "LIKE", "%$keyword%")
                    ->orWhere("params", "LIKE", "%$keyword%");
            });
        }

        if ($district_id > 0) {
            $query->where(function ($query) use ($district_id) {
                $query->where('district_id', $district_id);
            });
        }

        if ($ward_id > 0) {
            $query->where(function ($query) use ($ward_id) {
                $query->where('ward_id', $ward_id);
            });
        }

        if ($street_id > 0) {
            $query->where(function ($query) use ($street_id) {
                $query->where('street_id', $street_id);
            });
        }

        if ($filter_direction > 0) {
            $query->where(function ($query) use ($filter_direction) {
                $query->where('home_direction', $filter_direction);
            });
        }

        if ($filter_acreage > 0) {
            $range_acreage = RangeAcreage::where('id', 1)->first();
            if ($range_acreage) {
                $min = (int)$range_acreage->min;
                $max = (int)$range_acreage->max;
                $query->whereHas('areas', function ($query) use ($min, $max) {
                    $query->whereBetween('value', [$min, $max]);
                });
            }
        }
        if ($filter_price > 0) {
            $range_price = RangePrice::where('id', $filter_price)->first();
            if ($range_price) {
                $min_price = (int)$range_price->min;
                $max_price = (int)$range_price->max;
                $query->where(function ($query) use ($min_price, $max_price) {
                    $query->whereBetween('price', [$min_price, $max_price]);
                });
            }
        }

        return $query;
    }

    public function renderSchema()
    {
        $render = '';
        $render .= Schema::product()
            ->url(route('paths.parse', $this->getPathAlias()))
            ->name($this->getOriginal('title'))
            ->offers([
                '@type' => 'Offer',
                'price' => number_format($this->price, 1, '.', ''),
                'priceCurrency' => 'USD'
            ])
            ->image([
                '@type' => 'imageObject',
                'url' => route('image.manipulate', [$this->getSetting('thumbnail_width'), $this->getSetting('thumbnail_height'), $this->getImagePath()]),
                'width' => $this->getSetting('thumbnail_width'),
                'height' => $this->getSetting('thumbnail_height')
            ])
            ->mainEntityOfPage(true)
            ->toScript();
        return $render;
    }

    public function renderBreadcrumb()
    {
        $render = '';
        $render .= Schema::breadcrumbList()
            ->mainEntityOfPage(true)
            ->itemListElement([
                [
                    "@type" => "ListItem",
                    "position" => 1,
                    "item" => [
                        "@id" => route('home.index'),
                        "name" => SITENAME
                    ]
                ],
                [
                    "@type" => "ListItem",
                    "position" => 2,
                    "item" => [
                        "@id" => route('categories.show', $this->category->getPathAlias()),
                        "name" => $this->category->title
                    ]
                ],
                [
                    "@type" => "ListItem",
                    "position" => 3,
                    "item" => [
                        "@id" => route('paths.parse', $this->getPathAlias()),
                        "name" => $this->title
                    ]
                ]
            ])
            ->toScript();
        return $render;
    }
}
