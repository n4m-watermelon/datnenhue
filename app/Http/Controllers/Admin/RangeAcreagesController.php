<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreRangeAcreagesRequest;
use App\Models\RangeAcreage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RangeAcreagesController extends Controller
{

    public function index()
    {
        $acreages = RangeAcreage::all();
        return view('admin.range_acreages.index', compact('acreages'));
    }

    public function create()
    {
        $acreage = new RangeAcreage;
        return view('admin.range_acreages.create', compact('acreage'));
    }

    public function store(StoreRangeAcreagesRequest $request)
    {
        $data = $request->all();
        RangeAcreage::create($data);
        return redirect()->route('admin::range_acreages.index')->with('status', 'Thêm mới thành công!');
    }

    public function show($id)
    {
        $acreage = RangeAcreage::find($id);
        return view('admin.range_acreages.show', compact('acreage'));
    }

    public function edit($id)
    {
        $acreage = RangeAcreage::find($id);
        return view('admin.range_acreages.edit', compact('acreage'));
    }

    public function update(StoreRangeAcreagesRequest $request, $id)
    {
        $acreage = RangeAcreage::find($id);
        $data = $request->all();
        $acreage->update($data);
        return redirect()->route('admin::range_acreages.index')->with('status', 'Cập nhật thông tin thành công!');
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $acreage = RangeAcreage::find($id);
            $acreage->delete();
            return response()->json(['msg' => 'Xóa dữ liệu thành công!', 'status' => '200']);
        }
        return redirect()->route('admin::range_acreages.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }
}
