<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\Category\Interfaces\CategoryInterface;
use App\Repositories\Estate\Interfaces\EstateInterface;

class PathsController extends Controller
{
    protected $estateRepository;

    protected $categoryRepository;

    public function __construct(EstateInterface $estateRepository, CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->estateRepository = $estateRepository;
    }

    /**
     * @param $path_alias
     * @return \Illuminate\Http\Response
     */
    public function parse($path_alias)
    {
        $aliasSet = explode('/', $path_alias);
        $itemAlias = end($aliasSet);
        array_pop($aliasSet);
        $categoryAlias = end($aliasSet);
        $categoryPathAlias = implode('/', $aliasSet);
        $category = $this->categoryRepository->getModel()->whereRaw('title_alias = ? and public = 1',
                [$categoryAlias])->first();
        if (!empty($category) && $category->getPathAlias() == $categoryPathAlias) {
            if ($category->ancestors()->where('public', '!=', 1)->count() != 0) {
                return response()->view('frontend.errors.404', [], 404);
            }
            $model = 'App\\Models\\' . $category->component;
            $item = $model::whereRaw('title_alias = ? and public = 1', [$itemAlias])
                ->whereHas('category', function ($query) use ($categoryAlias) {
                    $query->whereRaw('title_alias = ? and public = 1', [$categoryAlias]);
                })->first();
            if (!empty($item)) {
                $controllerClass = 'App\\Http\\Controllers\\' . str_plural($category->component) . 'Controller';
                return \App::make($controllerClass)->show($item->id);
            } else {
                return response()->view('frontend.errors.404', [], 404);
            }
        } else {
            return response()->view('frontend.errors.404', [], 404);
        }
    }
}
