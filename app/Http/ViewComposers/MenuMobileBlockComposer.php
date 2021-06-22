<?php

namespace App\Http\ViewComposers;

use App\Models\MenuItem;
use Illuminate\View\View;

class MenuMobileBlockComposer

{
    /**
     *  View Compose
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $groupItems = MenuItem::where('group_id', 1)->where('public', 1)->where('depth', '=', 0);
        if ($groupItems->count() != 0) {
            $menu = "<ul>";
            $menuItems = $groupItems->orderBy('lft', 'asc')->get();
            foreach ($menuItems as $level1) {
                $groupItems = $level1->children()->where('public', '=', 1);
                $subCount = $groupItems->count();
                if ($subCount != 0) {
                    $liTagsParams = '';
                    $aTagsParams = '';
                    $caretIcon = '';
                } else {
                    $liTagsParams = null;
                    $aTagsParams = null;
                    $caretIcon = null;
                }
                $menu .= '<li ' . $liTagsParams . '>';
                if ($level1->type_id != 0) {
                    $model = $level1->type->model;
                    if (!empty($model)) {
                        $model = 'App\\' . $model;
                        $obj = $model::find($level1->data_id);
                        if ($obj)
                            $menu .= '<a ' . $aTagsParams . ' href="' . route($level1->type->route, $obj->getPathAlias()) . '">' . $level1->title . $caretIcon . '</a>';
                        else
                            $menu .= '<a href="#" ' . $aTagsParams . '>' . $level1->title . $caretIcon . '</a>';
                    } else {
                        $menu .= '<a ' . $aTagsParams . ' href="' . route($level1->type->route) . '">' . $level1->title . $caretIcon . '</a>';
                    }
                } else {
                    $menu .= '<a href="' . $level1->link . '">' . $level1->title . $caretIcon . '</a>';
                }
                // Start level 2
                if ($subCount != 0) {
                    $level2Items = $groupItems->orderBy('lft', 'asc')->get();
                    $menu .= '<ul>';
                    foreach ($level2Items as $level2) {
                        $groupItems = $level2->children()->where('public', '=', 1);
                        $subCount = $groupItems->count();
                        if ($subCount != 0) {
                            $liTagsParams = '';
                            $aTagsParams = '';
                            $caretIcon = '';
                        } else {
                            $liTagsParams = null;
                            $aTagsParams = null;
                            $caretIcon = null;
                        }
                        $menu .= '<li>';
                        if ($level2->type_id != 0) {
                            $model = $level2->type->model;
                            if (!empty($model)) {
                                $model = 'App\\' . $model;
                                $obj = $model::find($level2->data_id);
                                if ($obj)
                                    $menu .= '<a href="' . route($level2->type->route, $obj->getPathAlias()) . '">' . $level2->title . $caretIcon . '</a>';
                                else
                                    $menu .= '<a href="#">' . $level2->title . $caretIcon . '</a>';
                            } else {
                                $menu .= '<a href="' . route($level2->type->route) . '">' . $level2->title . $caretIcon . '</a>';
                            }
                        } else {
                            $menu .= '<a href="' . $level2->link . '">' . $level2->title . $caretIcon . '</a>';
                        }
                        // Start level 3
                        if ($subCount != 0) {
                            $level3Items = $groupItems->orderBy('lft', 'asc')->get();
                            $menu .= '<ul>';
                            foreach ($level3Items as $level3) {
                                $groupItems = $level3->children()->where('public', '=', 1);
                                $subCount = $groupItems->count();
                                if ($subCount != 0) {
                                    $liTagsParams = '';
                                    $aTagsParams = '';
                                    $caretIcon = ' ';
                                } else {
                                    $liTagsParams = null;
                                    $aTagsParams = null;
                                    $caretIcon = null;
                                }
                                $menu .= '<li>';
                                if ($level3->type_id != 0) {
                                    $model = $level3->type->model;
                                    if (!empty($model)) {
                                        $model = 'App\\' . $model;
                                        $obj = $model::find($level3->data_id);
                                        $menu .= '<a href="' . route($level3->type->route, $obj->getPathAlias()) . '">' . $level3->title . $caretIcon . '</a>';
                                    } else {
                                        $menu .= '<a href="' . route($level3->type->route) . '">' . $level3->title . $caretIcon . '</a>';
                                    }
                                } else {
                                    $menu .= '<a href="' . $level3->link . '">' . $level3->title . $caretIcon . '</a>';
                                }
                                // Start level 4
                                // End level 4
                                $menu .= '</li>';
                            }
                            $menu .= '</ul>';
                        }
                        // End level 3
                        $menu .= '</li>';
                    }
                    $menu .= '</ul>';
                }
                // End level 2
                $menu .= '</li>';
            }
            $menu .= "</ul>";
        } else {
            $menu = null;
        }
        $view->with([
            'mobile_menu' => $menu
        ]);
    }
}
