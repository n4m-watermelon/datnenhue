<?php

namespace App\Http\Controllers;

use App\Models\Street;
use App\Models\Ward;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getWard(Request $request)
    {
        if ($request->ajax()) {
            $district_id = $request->get('_district');
            $wards = Ward::where('district_id', $district_id)->get();
            $streets = Street::where('district_id', $district_id)->get();
            return response()->json([
                'wards'   => $wards,
                'streets' => $streets
            ],200);
        }
    }
}
