<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class DistrictBlockComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $block = $view->getData()['block'];
        $view->with([
            'districts' => $block->districts
        ]);
    }
}