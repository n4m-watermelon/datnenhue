<?php

namespace App\Http\Controllers;

use App\Repositories\Estate\Interfaces\EstateInterface;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * @var EstateInterface
     */
    protected $estateRepository;

    /**
     * SearchController constructor.
     * @param EstateInterface $estateRepository
     */
    public function __construct(EstateInterface $estateRepository)
    {
        $this->estateRepository = $estateRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSearch(Request $request)
    {
        $utility        = $request->get('utility');
        $district_id    = $request->get('district_id');
        $filter_price   = $request->get('price');
        $filter_unit    = $request->input('unit');
        $filter_acreage = $request->get('acreage');
        $items = $this->estateRepository->getSearchByOption($utility, $district_id, $filter_price, $filter_unit,
            $filter_acreage, 16);
        return view('frontend.search.index', compact('items'));
    }
}
