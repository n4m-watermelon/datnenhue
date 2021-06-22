<?php

namespace App\Providers;

use App\Http\ViewComposers\BoxBlockComposer;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        view()->composer('frontend.blocks.districts.index', 'App\Http\ViewComposers\DistrictBlockComposer');
        view()->composer('frontend.blocks.articles.index', 'App\Http\ViewComposers\ArticleBlockComposer');
        view()->composer('frontend.blocks.main_menu', 'App\Http\ViewComposers\MenuMainComposer');
        view()->composer('frontend.blocks.menus.show', 'App\Http\ViewComposers\MenuBlockComposer');
        view()->composer('frontend.blocks.menu_mobile', 'App\Http\ViewComposers\MenuMobileBlockComposer');
        view()->composer('frontend.blocks.sliders.show', 'App\Http\ViewComposers\SliderBlocksComposer');
        view()->composer('frontend.blocks.projects.index', 'App\Http\ViewComposers\ProjectBlockComposer');
        view()->composer('frontend.blocks.estates.index', 'App\Http\ViewComposers\EstateBlockComposer');
        view()->composer('frontend.blocks.contacts.show', 'App\Http\ViewComposers\ContactBlocksComposer');
        view()->composer('frontend.blocks.partners', 'App\Http\ViewComposers\PartnerBlockComposer');
        view()->composer('frontend.blocks.category_contacts.show', 'App\Http\ViewComposers\GroupContactBlocksComposer');
        view()->composer('frontend.layouts.sidebar.feature_box', 'App\Http\ViewComposers\FeaturedEstateBoxComposer');
        view()->composer('frontend.blocks.partners.index', 'App\Http\ViewComposers\PartnerBlockComposer');
        view()->composer('frontend.blocks.boxes.show', BoxBlockComposer::class);
        view()->composer('frontend.blocks.articles.list', 'App\Http\ViewComposers\ArticleBlockComposer@list');
        view()->composer('frontend.blocks.regions.index', 'App\Http\ViewComposers\RegionBlockComposer');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
