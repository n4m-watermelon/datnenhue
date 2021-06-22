<?php

namespace App\Http\ViewComposers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Helpers\Helper;
use Illuminate\View\View;

class ArticleBlockComposer
{
    /**
     * ArticleBlockComposer constructor.
     */
    public function __construct()
    {

    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $block = $view->getData()['block'];
        if (isset($block->params->category_id))
            $category = Category::find($block->params->category_id);
        if (!isset($category)) {
            $articles = [];
        } else {
            $public_cates = [];
            if (isset($block->params->include_sub_cates) && $block->params->include_sub_cates == 1) {
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

            $featurer = null;
            if ($block->params->feature_only == 1) {
                $featurer = ' and featured = 1';
            }
            $articles = Article::whereRaw('category_id in (' . $categories . ') and public = 1' . $featurer)
                ->orderBy('featured', 'desc')
                ->orderBy($block->params->orderBy, $block->params->direction)
                ->take($block->params->amount_of_data)
                ->get();
        }
        $view->with([
            'articles' => $articles,
            'category' => $category
        ]);
    }
    public function list(View $view)
    {
        $articles = $categories = [];
        $block = $view->getData()['block'];
        if (isset($block->params->category_id)) {
            $categories = Category::whereIn('id', $block->params->category_id)->get();
        }
        if (isset($categories)) {
            foreach ($categories as $cate) {
                $amount_of_data = $block->params->amount_of_data;
                $articles[$cate->id][] = Article::where("category_id", $cate->id)->where('public', 1)->orderBy($block->params->orderBy, $block->params->direction)
                    ->take($amount_of_data)
                    ->get();
                if (count($articles[$cate->id][0]) < $amount_of_data) {
                    $publicChildCates = $cate->immediateDescendants()->select('id')->where('public', 1)->get();
                    $arrayKeyChild = [];
                    foreach ($publicChildCates as $children) {
                        array_push($arrayKeyChild, $children->id);
                    }
                    if($arrayKeyChild){
                        $getChild = Article::whereIn("category_id", $arrayKeyChild)->where('public', 1)->orderBy($block->params->orderBy, $block->params->direction)
                            ->take($amount_of_data)
                            ->get();

                        if (count($articles[$cate->id][0]) == 0)
                        {
                            unset($articles[$cate->id][0]);
                            $articles[$cate->id][] = $getChild;
                        } else {
                            array_push($articles[$cate->id], $getChild);
                        }
                    }
                }
            }
        }
        $view->with([
            'categories' => $categories,
            'articles'   => $articles,
        ]);
    }
}
