<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Models\Box;
use App\Models\Estate;
use App\Repositories\Message\Interfaces\MessageInterface;

class PagesController extends Controller
{
    protected $estate;
    protected $messageRepository;

    //
    public function __construct(Estate $estate, MessageInterface $messageRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->estate = $estate;
    }

    public function getCseSearchGoogle()
    {
        return view('frontend.search.cse');
    }

    public function getForRent()
    {
        if (request()->get('box')) {
            $box = Box::find((int)request()->get('box'));
        }
        $min_price = null;
        $max_price = null;
        if (request()->has('p') && !empty(request()->get('p'))) {
            $prices = explode(',', request()->get('p'));
            $min_price = !empty($prices) && isset($prices[0]) ? $prices[0] : null;
            $max_price = !empty($prices) && isset($prices[1]) ? $prices[1] : null;
        }
        $min_acreage = null;
        $max_acreage = null;
        if (request()->get('a') && !empty(request()->get('a'))) {
            $acreages = explode(',', request()->get('a'));
            $min_acreage = !empty($acreages) && isset($acreages[0]) ? $acreages[0] : null;
            $max_acreage = !empty($acreages) && isset($acreages[1]) ? $acreages[1] : null;
        }
        $items = $this->estate->FilterSearch($min_price, $max_price, $min_acreage, $max_acreage)->paginate(10);
        return view('frontend.pages.rent', compact('items'));
    }

    /**
     * RenderMap
     *
     * @return mixed
     */
    public function renderMap()
    {
        $min_price = null;
        $max_price = null;
        if (request()->has('p') && !empty(request()->get('p'))) {
            $prices = explode(',', request()->get('p'));
            $min_price = !empty($prices) && isset($prices[0]) ? $prices[0] : null;
            $max_price = !empty($prices) && isset($prices[1]) ? $prices[1] : null;
        }
        $min_acreage = null;
        $max_acreage = null;
        if (request()->get('a') && !empty(request()->get('a'))) {
            $acreages = explode(',', request()->get('a'));
            $min_acreage = !empty($acreages) && isset($acreages[0]) ? $acreages[0] : null;
            $max_acreage = !empty($acreages) && isset($acreages[1]) ? $acreages[1] : null;
        }
        $items = \Cache::remember('locations_with_map', 10, function () use ($min_price, $max_price, $min_acreage, $max_acreage) {
            return $this->estate->FilterSearch($min_price, $max_price, $min_acreage, $max_acreage)->where('public', 1)->whereNotNull('lat')->whereNotNull('lng')->get();
        });
        $locations = [];
        foreach ($items as $k => $item) {
            $locations[$k]['title'] = $item->title;
            $locations[$k]['lat'] = $item->lat;
            $locations[$k]['lng'] = $item->lng;
            $locations[$k]['thumb'] = route('image.manipulate', [400, 165, $item->getImagePath()]);
            $locations[$k]['link'] = route('paths.parse', $item->getPathAlias());

        }
        $contents = view('frontend.pages.location')->with('location', $locations);
        return response($contents)->header('Content-Type', 'application/javascript');
    }

    /**
     *
     */
    public function getMapView()
    {
        return view('frontend.pages.map_view');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDepositEstate()
    {
        return view('frontend.pages.deposit');
    }

    /**
     *
     *
     * @param DepositRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDepositEstate(DepositRequest $request)
    {
        try {
            $this->messageRepository->createOrUpdate($request->input());
            return redirect()->route('page.deposit')->with('status','Ký gửi nhà đất thành công. Chúng tôi sẽ liên hệ vs bạn sớm nhất.');
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
}
