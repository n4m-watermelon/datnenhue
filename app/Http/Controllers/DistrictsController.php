<?php

namespace App\Http\Controllers;

use App\Repositories\Category\Interfaces\CategoryInterface;
use App\Repositories\District\Interfaces\DistrictInterface;
use App\Repositories\Estate\Interfaces\EstateInterface;
use Illuminate\Http\Request;

class DistrictsController extends Controller
{
    /**
     * @var DistrictInterface
     */
    protected $districtRepository;
    /**
     * @var CategoryInterface
     */
    protected $categoryRepository;

    protected $estateRepository;

    /**
     * DistrictsController constructor.
     * @param DistrictInterface $districtRepository
     * @param CategoryInterface $categoryRepository
     */
    public function __construct(
        DistrictInterface $districtRepository,
        CategoryInterface $categoryRepository,
        EstateInterface $estateRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->districtRepository = $districtRepository;
        $this->estateRepository = $estateRepository;
    }

    //
    public function showByDistrict(Request $request, $district_alias, $district_id)
    {

    }

    /**
     * @param $category
     * @param $district
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function getEstatesByCategoryAndDistrict($category, $district, $id)
    {
        $district = $this->districtRepository->getBySlugAndId($district, $id);
        if (!$district) {
            return response()->view('frontend.errors.404', [], 404);
        }
        $category = $this->categoryRepository->getByTitleAlias($category);
        if (!$category) {
            return response()->view('frontend.errors.404', [], 404);
        }
        // Get public child category
//        $publicChildCates = $category->immediateDescendants()->where('public', 1)->get();

        // Get all public descendants
        /*$descendants = $category->getDescendantsAndSelf();
        $publicSubCates = [];
        foreach ($descendants as $descendant) {
            if ($descendant->public == 1 && $descendant->ancestors()->where('public', '!=', 1)->count() == 0) {
                $publicSubCates[] = $descendant->id;
            }
        }*/
        $items = $this->estateRepository->getByDistrictId($district->id);

        return view('frontend.estates.category_district', compact('items', 'district', 'category'));

    }
}
