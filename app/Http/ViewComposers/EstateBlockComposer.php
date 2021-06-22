<?php

namespace App\Http\ViewComposers;

use App\Models\Category;
use App\Repositories\Category\Interfaces\CategoryInterface;
use App\Repositories\Estate\Interfaces\EstateInterface;
use Illuminate\View\View;

class EstateBlockComposer
{
    /**
     * @var EstateInterface
     */
    protected $estateRepository;
    /**
     * @var
     */
    protected $categoryRepository;

    /**
     * EstateBlockComposer constructor.
     * @param EstateInterface $estateRepository
     * @param CategoryInterface $categoryRepository
     */
    public function __construct(EstateInterface $estateRepository, CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->estateRepository = $estateRepository;
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $block = $view->getData()['block'];
        $filter = NULL;
        $category = NULL;
        if ($block->params->fillter == 'featured') {
            $filter = ' and featured = 1';
        } else if ($block->params->fillter == 'isNew') {
            $filter = ' and is_new = 1';
        } else {
            $filter = NULL;
        }
        if (isset($block->params->category_id) && $block->params->category_id > 0) {
            if (isset($block->params->category_id))
                $category = $this->categoryRepository->findById($block->params->category_id);
            if (!isset($category)) {
                $estates = [];
            } else {
                $public_categories = [];
                if ($block->params->include_sub_cate == 1) {
                    $descendantsAndSelf = $category->getDescendantsAndSelf();
                    foreach ($descendantsAndSelf as $cate) {
                        if ($cate->public == 1 && $cate->ancestors()->where('public', '!=', 1)->count() == 0) {
                            $public_categories[] = $cate->id;
                        }
                    }
                } else {
                    if ($category->public == 1 && $category->ancestors()->where('public', '!=', 1)->count() == 0) {
                        $public_categories[] = $block->params->category_id;
                    }
                }
                $categories = implode(',', $public_categories);
                $estates = $this->estateRepository->getEstateBlock($categories, $filter, $block);
            }
        } else {
            $estates = $this->estateRepository->getModel()->whereRaw('public = 1' . $filter)
                ->orderBy('featured', 'desc')
                ->orderBy($block->params->orderBy, $block->params->direction)
                ->take($block->params->amount_of_data)
                ->get();
        }

        $view->with([
            'estates' => $estates,
            'category' => $category
        ]);
    }
}
