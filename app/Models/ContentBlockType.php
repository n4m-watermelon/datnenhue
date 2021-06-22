<?php

namespace App\Models;

class ContentBlockType extends BaseModel
{
    public $timestamps = false;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'content_block_types';

    /**
     * Fillable field
     *
     * @var mixed
     */
    protected $fillable = [
        'title',
        'action',
    ];

    /**
     * ContentBlockType::blocks()
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blocks()
    {
        return $this->hasMany(ContentBlock::class, 'type_id');
    }

    /**
     * ContentBlockType::getList()
     *
     * @return
     */
    public static function getList()
    {
        $all = self::all(['id', 'title'])->toArray();
        $list = array_column($all, 'title', 'id');
        return $list;
    }
}
