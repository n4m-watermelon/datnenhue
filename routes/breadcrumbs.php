<?php

Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push('Trang chủ', route('home.index'));
});

Breadcrumbs::register('categories_show', function ($breadcrumbs, $category) {
    $breadcrumbs->parent('home');
    foreach (explode('/', $category->getPathAlias()) as $path) {
        $show = \App\Models\Category::where('title_alias', '=', $path)->first();
        if ($show) {
            $breadcrumbs->push($show->title, route('categories.show', $show->getPathAlias()));
        }
    }
});

Breadcrumbs::register('view_page', function ($breadcrumbs, $page) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push($page->title, route('pages.show', $page->id));
});

Breadcrumbs::register('paths_parse', function ($breadcrumbs, $estate) {
    $breadcrumbs->parent('categories_show', $estate->category);
    $breadcrumbs->push($estate->title, route('paths.parse', $estate->id));
});

Breadcrumbs::register('paths_parse_estate', function ($breadcrumbs, $estate) {
    if (!is_null($estate->categories)) {
        foreach ($estate->categories as $category)
            $breadcrumbs->parent('categories_show', $category);
    }
    $breadcrumbs->push($estate->title, route('paths.parse', $estate->getPathAlias()));
});
Breadcrumbs::register('show_categories_district', function ($breadcrumbs, $category, $district) {
    $breadcrumbs->parent('home');
    foreach (explode('/', $category->getPathAlias()) as $path) {
        $show = \App\Models\Category::where('title_alias', '=', $path)->first();
        if (!is_null($show)) {
            $breadcrumbs->push($show->title, route('categories.show', $show->getPathAlias()));
        }
    }
    $breadcrumbs->push($district->pre . ' ' . $district->name, route('categories.district', [$category->title_alias, $district->slug_name, $district->id]));
});

Breadcrumbs::register('show_categories_ward', function ($breadcrumbs, $category, $district, $ward) {
    $breadcrumbs->parent('home');
    foreach (explode('/', $category->getPathAlias()) as $path) {
        $show = \App\Models\Category::where('title_alias', '=', $path)->first();
        if (!is_null($show)) {
            $breadcrumbs->push($show->title, route('categories.show', $show->getPathAlias()));
        }
    }
    $breadcrumbs->push($district->name, route('categories.district', [$category->title_alias, $district->slug_name, $district->id]));
    $breadcrumbs->push($ward->pre . ' ' . $ward->name, route('categories.ward', [$category->title_alias, $district->slug_name, $ward->title_alias, $ward->id]));
});

