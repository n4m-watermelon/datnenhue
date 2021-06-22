<?php

namespace App\Http\ViewComposers;

use App\Models\Category;
use App\Repositories\Category\Interfaces\CategoryInterface;
use App\Repositories\Estate\Interfaces\EstateInterface;
use Illuminate\View\View;

class RegionBlockComposer
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
        $block     = $view->getData()['block'];
        $districts = $block->districts()->orderBy('id', 'desc')->take(5)->get();
        if($districts){
            foreach ($districts as $key => $district) {
                $district_id = $district->id;
                $districts[$key]['number'] = $this->estateRepository
                    ->getModel()
                    ->where('public', 1)->where('district_id', $district_id)
                    ->where('category_id', $block->id)
                    ->count();
            }
        }
        $view->with([
            'districts' => $districts
        ]);
    }
}
