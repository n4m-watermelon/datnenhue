<?php
namespace App\Http\ViewComposers;

use App\Models\MenuType;
use App\Models\MenuItem;
use Illuminate\View\View;

class MenuBlockComposer
{
    /**
     * Compose
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $block = $view->getData()['block'];
        $groupItems = MenuItem::where('group_id', '=', $block->params->group_id)->where('public', '=', 1)->where('depth', '=', 0);
        if ($groupItems->count() != 0) {
            $menu = '<ul id="menu-'.$block->id.'" class="menu">';
            $menuItems = $groupItems->orderBy('lft', 'asc')->get();
            foreach ($menuItems as $level1) {
                $menu .= '<li>';
                if ($level1->type_id != 0) {
                    $model = $level1->type->model;
                    if (!empty($model)) {
                        $model = 'App\\' . $model;
                        $obj = $model::find($level1->data_id);
                        if ($obj){
                            $menu .= '<a href="' . route($level1->type->route, $obj->getPathAlias()) . '"><i class="fa fa-caret-right"></i>' . $level1->title . '</a>';
                        }else{
                            $menu .= '<a href="#">' . $level1->title . '</a>';
                        }
                    } else {
                        $menu .= '<a href="' . route($level1->type->route) . '"><i class="fa fa-caret-right"></i>' . $level1->title . '</a>';
                    }
                } else {
                    $menu .= '<a href="' . $level1->link . '"><i class="fa fa-caret-right"></i>' . $level1->title . '</a>';
                }
                $menu .= '</li>';
            }
            $menu .= '</ul>';
        } else {
            $menu = null;
        }
        $view->with([
            'menus' => $menu
        ]);
    }
}
