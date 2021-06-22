<?php

namespace App\Http\ViewComposers;

use App\Models\Category;
use App\Models\Project;
use Illuminate\View\View;
class ProjectBlockComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $block = $view->getData()['block'];
        if (isset($block->params->category_id))
            $category = Category::find($block->params->category_id);
        if (!isset($category)) {
            $projects = [];
        } else {
            $public_cates = [];
            if ($block->params->include_sub_cates == 1) {
                $descendantsAndSelf = $category->getDescendantsAndSelf();
                foreach ($descendantsAndSelf as $cate) {
                    if ($cate->public == 1 && $cate->ancestors()->where('public', '!=', 1)->count() == 0) {
                        $public_cates[] = $cate->id;
                    }
                }
            } else {
                if ($category->public == 1 && $category->ancestors()->where('public', '!=', 1)->count() == 0) {
                    $public_cates[] = $block->params->category_id;
                }
            }
            $categories = implode(',', $public_cates);

            $fillter = null;
            if ($block->params->fillter == 'featured') {
                $fillter = ' and featured = 1';
            }else if($block->params->fillter == 'isNew'){
                $fillter = ' and is_new = 1';
            }else{
                $fillter = null;
            }
            $projects = Project::whereRaw('category_id in (' . $categories . ') and public = 1' . $fillter)
                ->orderBy('featured', 'desc')
                ->orderBy($block->params->orderBy, $block->params->direction)
                ->take($block->params->amount_of_data)
                ->get();
        }
        $view->with([
            'projects' => $projects,
            'category' => $category
        ]);
    }
}

?>
