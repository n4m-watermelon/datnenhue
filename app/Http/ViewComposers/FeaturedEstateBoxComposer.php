<?php

namespace App\Http\ViewComposers;

use App\Models\Estate;
use Illuminate\View\View;

class FeaturedEstateBoxComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $take = 5;
        if ( (request()->route()->getName()) == 'paths.parse'){
            $take = 3;
        }
        $featured_products = Estate::whereRaw('public = 1 and featured = 1')->inRandomOrder()->take($take)->get();
        $view->with([
            'featured_product' => $featured_products
        ]);
    }
}
