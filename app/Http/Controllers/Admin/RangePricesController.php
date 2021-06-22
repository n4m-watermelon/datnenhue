<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreRangePriceRequest;
use App\Models\RangePrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RangePricesController extends Controller
{

    public function index()
    {
        $prices = RangePrice::all();
        return view('admin.range_prices.index', compact('prices'));
    }


    public function create()
    {
        $price = new RangePrice;
        return view('admin.range_prices.create', compact('price'));
    }

    public function store(StoreRangePriceRequest $request)
    {
        $data = $request->all();
        RangePrice::create($data);
        return redirect()->route('admin::range_prices.index')->with('status', ' Thêm mới thành công!');
    }

    public function show($id)
    {
        $price = RangePrice::find($id);
        return view('admin.range_prices.show', compact('price'));
    }

    public function edit($id)
    {
        $price = RangePrice::findOrFail($id);
        return view('admin.range_prices.edit', compact('price'));
    }

    public function update(StoreRangePriceRequest $request, $id)
    {
        $price = RangePrice::find($id);
        $data = $request->all();
        $price->update($data);
        return redirect()->route('admin::range_prices.index')->with('status', ' Cập nhật thông tin thành công!');
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $price = RangePrice::find($id);
            $price->delete();
            return response()->json(['msg' => 'Xóa dữ liệu thành công!', 'status' => '200']);
        }
        return redirect()->route('admin::range_acreages.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }
}
