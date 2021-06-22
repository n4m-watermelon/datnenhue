<?php

namespace App\Models;

class MenuType extends BaseModel
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'menu_types';

    /**
     * Fillable filed
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'component',
        'route',
        'model',
        'get_data_code'
    ];

    /**
     * MenuType::menu_iems()
     *
     * @return Relationships
     */
    public function menus()
    {
        return $this->hasMany(MenuItem::class, 'type_id');
    }

    /**
     * MenuType::getList()
     *
     * @param array $prependList
     * @param array $appendList
     * @return array
     */
    public static function getList($prependList = [], $appendList = [])
    {
        $list = array_column(self::all()->toArray(), 'title', 'id');
        foreach ($list as $key => $title) {
            $prependList['Liên kết nội bộ'][$key] = $title;
        }
        foreach ($appendList as $key => $title) {
            $prependList['Khác'][$key] = $title;
        }
        return $prependList;
    }
}
