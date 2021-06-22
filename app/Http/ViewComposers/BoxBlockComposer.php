<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class BoxBlockComposer
{
    public function compose(View $view)
    {
        $block = $view->getData()['block'];
        $custom_link = isset($block->params->custom_link) && !empty($block->params->custom_link) ?
            $block->params->custom_link : 'javascript::void()';
        $view->with([
            'boxes'       => $block->boxes,
            'custom_link' => $custom_link
        ]);
    }
}