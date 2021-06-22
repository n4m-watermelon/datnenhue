<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MenuGroup extends BaseModel
{
    protected $fillable = [
        'title',
        'title_alias',
        'description',
        'created_by',
        'updated_by'
    ];


    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('created_at', function (Builder $builder) {
            $builder->orderBy('created_at', 'asc');
        });
        static::creating(function ($menu_group) {
            $menu_group->created_by = Auth::user()->id;
            $menu_group->updated_by = Auth::user()->id;
        });
        static::updating(function ($menu_group) {
            $menu_group->updated_by = Auth::user()->id;
        });
        static::deleting(function ($menu_group) {

        });
    }

    /**
     * MenuGroup::menus()
     *
     * @return Relationship
     */
    public function menu_items()
    {
        return $this->hasMany(MenuItem::class, 'group_id');
    }

    /**
     * MenuGroup::getList()
     * @param array $prependList
     * @param array $appendList
     * @return array
     */
    public static function getList($prependList = [], $appendList = [])
    {
        $all = self::all(['id', 'title'])->toArray();
        $list = array_column($all, 'title', 'id');
        foreach ($list as $key => $title)
            $prependList[$key] = $title;
        foreach ($appendList as $key => $title)
            $prependList[$key] = $title;
        return $prependList;
    }
}
