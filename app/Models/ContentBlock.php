<?php

namespace App\Models;

use App\Http\Traits\EditedByTrait;
use App\Http\Traits\GetPublicTrait;
use Illuminate\Support\Facades\Auth;

class ContentBlock extends BaseModel
{
    use GetPublicTrait, EditedByTrait;
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'content_blocks';

    /**
     * Fillable field
     *
     * @var mixed
     */
    protected $fillable = [
        'title',
        'title_alias',
        'description',
        'public',
        'type_id',
        'position',
        'ordering',
        'params',
        'created_by',
        'updated_by'
    ];

    public static function boot()
    {
        parent::boot();
        if (\request()->segment(1) == 'admin') {
            static::creating(function ($block) {
                $block->created_by = Auth::user()->id;
                $block->updated_by = Auth::user()->id;
            });
            static::updating(function ($block) {
                $block->updated_by = Auth::user()->id;
            });
        }
    }

    /**
     * ContentBlock::type()
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(ContentBlockType::class, 'type_id');
    }

    /**
     * ContentBlock::getParamsAttribute()
     *
     * @param mixed $value
     * @return object
     */
    public function getParamsAttribute($value)
    {
        return json_decode($value, false);
    }

    /**
     * ContentBlock::setParamsAttribute()
     *
     * @param mixed $value
     * @return void
     */
    public function setParamsAttribute($value)
    {
        $this->attributes['params'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    /**
     * ContentBlock::getBlockViewAttribute()
     *
     * @param $value
     * @return string
     */
    public function getBlockViewAttribute($value)
    {
        $actionParse = explode('BlocksComposer@', $this->type->action);
        return str_plural(snake_case($actionParse[0])) . '.' . $actionParse[1];
    }

    /**
     * ContentBlock::getOrderingAttribute()
     *
     * @param mixed $ordering
     * @return
     */
    public function getOrderingAttribute($ordering)
    {
        if ($ordering == 9999) {
            $ordering = null;
        }
        return $ordering;
    }

    /**
     * ContentBlock::setOrderingAttribute()
     *
     * @param mixed $ordering
     * @return void
     */
    public function setOrderingAttribute($ordering)
    {
        if (empty($ordering)) {
            $ordering = 9999;
        }
        $this->attributes['ordering'] = $ordering;
    }

    /**
     * ContentBlock::countBlocks()
     *
     * @param mixed $positions
     * @return
     */
    public function countBlocks($positions)
    {
        if (is_array($positions)) {
            $totalCount = 0;
            foreach ($positions as $position) {
                $totalCount += self::wherePublic(1)->wherePosition($position)->count('id');
            }
            return $totalCount;
        }
        return self::wherePublic(1)->wherePosition($positions)->count('id');
    }

    /**
     * ContentBlock::loadBlocks()
     *
     * @param mixed $position
     * @return
     */
    public function loadBlocks($position)
    {
        return self::wherePublic(1)->wherePosition($position)->orderBy('ordering', 'asc')->get();
    }

    /**
     * ContentBlock::districts()
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function districts()
    {
        return $this->belongsToMany(District::class, 'content_block_district', 'content_block_id', 'district_id');
    }


    /**
     * ContentBlock::districts()
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function boxes()
    {
        return $this->belongsToMany(Box::class, 'content_block_boxes', 'content_block_id', 'box_id');
    }
}
