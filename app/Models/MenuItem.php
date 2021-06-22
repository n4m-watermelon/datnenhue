<?php

namespace App\Models;

use App\Http\Traits\GetEditedAtTrait;
use App\Scopes\PublishedScope;
use Baum\Node;
use App\Http\Traits\EditedByTrait;
use App\Http\Traits\GetPublicTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Node
{
    use GetPublicTrait, EditedByTrait, GetEditedAtTrait;
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'menu_items';
    /**
     * Fillable field
     *
     * @var mixed
     */
    protected $fillable = [
        'group_id',
        'type_id',
        'title',
        'data_id',
        'link',
        'public',
        'created_by',
        'updated_by'
    ];

    /**
     * Boot()
     */
    public static function boot()
    {
        parent::boot();
        if (\request()->segment(1) == 'admin') {
            static::creating(function ($menu_item) {
                $menu_item->created_by = Auth::user()->id;
                $menu_item->updated_by = Auth::user()->id;
            });
            static::updating(function ($menu_item) {
                $menu_item->updated_by = Auth::user()->id;
            });
            static::deleting(function ($menu_item) {

            });
        }else{
            static::addGlobalScope(new PublishedScope);
        }
    }

    /**
     * MenuItem::group()
     *
     * @return Ralationship
     */
    public function group()
    {
        return $this->belongsTo(MenuGroup::class, 'group_id');
    }

    /**
     * MenuItem::type()
     *
     * @return Ralationship
     */
    public function type()
    {
        return $this->belongsTo(MenuType::class, 'type_id');
    }

    /**
     * Return an key-value array indicating the node's depth with $seperator
     *
     * @param string $column
     * @param string $key
     * @param string $seperator
     * @param mixed $scopedFieldsValues
     * @return Array
     */
    public static function getNestedList($column, $key = null, $seperator = ' ', $scopedFieldsValues = [])
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
        }, $nodes), array_map(function ($node) use ($seperator, $depthColumn, $column) {
            return str_repeat($seperator, $node[$depthColumn]) . $node[$column];
        }, $nodes));
    }

    /**
     * MenuItem::getList()
     *
     * @param array $scopedFieldsValues
     * @param array $prependList
     * @param array $appendList
     * @return array
     */
    public static function getList($scopedFieldsValues = [], $prependList = [], $appendList = [])
    {
        $nestedList = self::getNestedList('title', 'id', '|---&nbsp;&nbsp;', $scopedFieldsValues);
        foreach ($nestedList as $key => $title) {
            $prependList[$key] = $title;
        }
        foreach ($appendList as $key => $title) {
            $prependList[$key] = $title;
        }
        return $prependList;
    }
}
