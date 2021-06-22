<?php

namespace App\Http\ViewComposers;

use App\Repositories\Eloquent\SliderResponsitoryInterface;
use Illuminate\View\View;

class SliderBlocksComposer
{
    /**
     * @var SliderResponsitoryInterface
     */
    protected $sliderRepo;

    /**
     * SliderBlocksComposer constructor.
     * @param SliderResponsitoryInterface $sliderRepo
     */
    public function __construct(SliderResponsitoryInterface $sliderRepo)
    {
        $this->sliderRepo = $sliderRepo;
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $block = $view->getData()['block'];
        $sliders = $this->sliderRepo->findOnlyPublishedByGroup($block->params->group_id);
        $view->with('sliders', $sliders);
    }
}
