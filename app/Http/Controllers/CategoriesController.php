<?php

namespace App\Http\Controllers;

use App\Repositories\Category\Interfaces\CategoryInterface;
use App\Repositories\Estate\Interfaces\EstateInterface;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    /**
     * @var CategoryInterface
     */
    protected $categoryRepository;
    /**
     * @var EstateInterface
     */
    protected $estateRepository;

    /**
     * CategoriesController constructor.
     * @param CategoryInterface $categoryRepository
     * @param EstateInterface $estateRepository
     */
    public function __construct(CategoryInterface $categoryRepository, EstateInterface $estateRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->estateRepository = $estateRepository;
    }

    /**
     * CategoriesController@show
     *
     * @param $path_alias
     * @return View
     */
    public function show($path_alias)
    {
        // Parse path_alias
        $aliasSet = explode('/', $path_alias);
        $title_alias = end($aliasSet);
        $category = $this->categoryRepository->getModel()->whereRaw('title_alias = ? and public = 1', [$title_alias])
            ->first();
        if (!empty($category) && $category->getPathAlias() == $path_alias) {
            // Check public status of ancestors
            if ($category->ancestors()->where('public', '!=', 1)->count() != 0) {
                return response()->view('frontend.errors.404', [], 404);
            }
            // Get public child category
            $publicChildCates = $category->immediateDescendants()->where('public', '=', 1)->get();

            // Get all public descendants
            $descendants = $category->getDescendantsAndSelf();
            $publicSubCates = [];
            foreach ($descendants as $descendant) {
                if ($descendant->public == 1 && $descendant->ancestors()->where('public', '!=', 1)->count() == 0) {
                    $publicSubCates[] = $descendant->id;
                }
            }
            // Get all items in public descendant and self
            $model = 'App\\Models\\' . $category->component;
            if (method_exists(with(new $model), 'getSetting')) {
                $record_per_page = with(new $model)->getSetting('record_per_page');
            } else {
                $record_per_page = \App\Models\Setting::getSetting('site')->record_per_page;
            }
            if ($category->component == 'Estate') {
                $items = $model::whereIn('category_id', $publicSubCates)->where('public', 1)
                    ->orderBy('featured', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate($record_per_page);
            } else if ($category->component == 'Contact') {
                $items = $model::whereIn('category_id', $publicSubCates)->where('public', '=', 1)
                    ->orderBy('created_at', 'desc')
                    ->paginate($record_per_page);
            } else {
                $items = $model::whereIn('category_id', $publicSubCates)->where('public', '=', 1)
                    ->orderBy('featured', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate($record_per_page);
            }
            return view('frontend.categories.' . Str::snake($category->component) . '.show', compact('category', 'publicChildCates', 'items'));
        } else {
            return response()->view('frontend.errors.404', [], 404);
        }
    }
}
