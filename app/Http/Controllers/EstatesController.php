<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\District;
use App\Models\Estate;
use App\Models\RangeAcreage;
use App\Models\RangePrice;
use App\Repositories\Estate\Interfaces\EstateInterface;
use App\Repositories\Utility\Interfaces\UtilityInterface;

class EstatesController extends Controller
{

    protected $utilityRepository;
    /**
     * @var
     */
    protected $estateRepository;

    /**
     * EstatesController constructor.
     * @param EstateInterface $estateRepository
     * @param UtilityInterface $utilityRepository
     */
    public function __construct(EstateInterface $estateRepository, UtilityInterface $utilityRepository)
    {
        $this->estateRepository = $estateRepository;
        $this->utilityRepository = $utilityRepository;
    }

    public function detail($title_alias, $id)
    {
        $estate = $this->estateRepository->findOrFail($id);
        if ($estate->title_alias != $title_alias){
            return response()->view('frontend.errors.404', [], 404);
        }
        $options = [];
        $options['link'] = route('estate.detail', [$estate->title_alias, $estate->id]);
        $options['image'] = route('image.manipulate', [100, 100, $estate->getImagePath()]);
        $area_product = '';
        if (!is_null($estate->areas)) {
            foreach ($areas = $estate->areas as $area) {
                if ($area === $areas->last()) {
                    $area_product .= $area->value;
                } else {
                    $area_product .= $area->value . ',';
                }
            }
        }
        $options['areas'] = $area_product;
        $same_districts = $this->estateRepository->getModel()->whereRaw('public = 1 and id != ' . $estate->id)->where([
            'district_id' => $estate->district_id
        ])->orderBy("created_at", 'desc')->paginate(12);
        $contact = null;
        if (!is_null($estate->contact)) {
            $contact = $estate->contact;
        } else {
            $contact = Contact::find($estate->getSetting('contact_id'));
        }
        if (request()->ajax()) {
            return view('frontend.estates.presult', compact('same_districts'));
        }
        return view('frontend.estates.show', compact('estate', 'contact', 'same_districts'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $estate = $this->estateRepository->findOrFail($id);
        $options = [];
        $options['link']  = route('paths.parse', $estate->getPathAlias());
        $options['image'] = route('image.manipulate', [100, 100, $estate->getImagePath()]);
        $area_product = '';
        if (!is_null($estate->areas)) {
            foreach ($areas = $estate->areas as $area) {
                if ($area === $areas->last()) {
                    $area_product .= $area->value;
                } else {
                    $area_product .= $area->value . ',';
                }
            }
        }
        $options['areas'] = $area_product;
        $same_districts = $this->estateRepository->getModel()->whereRaw('public = 1 and id != ' . $estate->id)->where([
            'district_id' => $estate->district_id
        ])->orderBy("created_at",'desc')->paginate(12);
        $contact = null;
        if (!is_null($estate->contact)) {
            $contact = $estate->contact;
        } else {
            $contact = Contact::find($estate->getSetting('contact_id'));
        }
        if (request()->ajax()) {
            return view('frontend.estates.presult', compact('same_districts'));
        }
        return view('frontend.estates.show', compact('estate', 'contact', 'same_districts'));
    }

    /**
     * EstatesController::range_prices()
     *
     * @param $min
     * @param $max
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function range_prices($min, $max)
    {
        $price = RangePrice::whereRaw('min = ? and max = ?', [$min, $max])->first();
        $min_price = number_format((float)$price->min, 4, '.', '');
        $max_price = number_format((float)$price->max, 4, '.', '');
        if (!$price) {
            return response()->view('frontend.errors.404', [], 404);
        }
        $record_per_page = \App\Models\Setting::getSetting('site')->record_per_page;
        $items = Estate::SearchByPrice($min_price, $max_price)->where('public', 1)->paginate($record_per_page);
        return view('frontend.estates.range_prices', compact('items', 'price'));
    }

    /**
     * EstatesController::range_acreages()
     *
     * @param $min
     * @param $max
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function range_acreages($min, $max)
    {
        $acreage = RangeAcreage::whereRaw('min = ? and max = ?', [$min, $max])->first();
        $min = (int)$acreage->min;
        $max = (int)$acreage->max;
        if (!$acreage) {
            return response()->view('frontend.errors.404', [], 404);
        }
        $record_per_page = \App\Models\Setting::getSetting('site')->record_per_page;
        $items = Estate::with('areas')->whereHas('areas', function ($query) use ($min, $max) {
            $query->whereBetween('value', [$min, $max]);
        })->where('public', 1)->paginate($record_per_page);
        return view('frontend.estates.range_acreages', compact('items', 'acreage'));
    }

    /**
     * EstatesController::district()
     *
     * @param $path_alias
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function district($path_alias, $id)
    {
        $district = District::where('slug_name', $path_alias)->where('id', $id)->first();
        if (empty($district))
            return response()->view('frontend.errors.404', [], 404);
        $record_per_page = \App\Setting::getSetting('site')->record_per_page;
        $items = Estate::where('district_id', $district->id)->orderByDesc('id')->paginate($record_per_page);
        return view('frontend.estates.district', compact('district', 'items'));
    }

    public function getByUtility($utility)
    {
        $utility = $this->utilityRepository->getByTitleAlias($utility);
        if (!$utility) {
            return response()->view('frontend.errors.404', [], 404);
        }

        $record_per_page = \App\Models\Setting::getSetting('site')->record_per_page;

        $items = $this->estateRepository->getByUtility($utility->id, $record_per_page);

        return view('frontend.estates.utility', compact('utility', 'items'));
    }
}
