<?php
namespace App\Http\Traits;
/**
 * GetListTrait
 *
 * @package Luni CMS
 * @author Jackie Do <anhvudo@gmail.com>
 * @copyright 2015
 * @version $Id$
 * @access public
 */
trait GetListTrait
{
    /**
     * getList()
     *
     * @param array $prependList
     * @param array $appendList
     * @return array
     */
    public static function getList($prependList = [], $appendList = [])
    {
        $all = self::all(['id', 'title'])->toArray();
        $list = array_column($all, 'title', 'id');
        foreach ($list as $key => $title) {
            $prependList[$key] = $title;
        }
        foreach ($appendList as $key => $title) {
            $prependList[$key] = $title;
        }
        return $prependList;
    }

}
