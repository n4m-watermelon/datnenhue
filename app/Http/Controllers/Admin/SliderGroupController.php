<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSliderGroupRequest;
use App\Models\SliderGroup;
use Illuminate\Http\Request;

class SliderGroupController extends Controller
{

    public function index()
    {
        $slider_groups = SliderGroup::all();
        return view('admin.slider_groups.index', compact('slider_groups'));
    }


    public function create()
    {
        $slider_group = new SliderGroup;
        return view('admin.slider_groups.create', compact('slider_group'));
    }


    public function store(StoreSliderGroupRequest $request)
    {
        SliderGroup::create($request->all());
        return redirect()->route('admin::slider_groups.index')->with('status', 'Thêm mới thành công !');
    }

    public function show($id)
    {
        $slider_group = SliderGroup::find($id);
        return view('admin.slider_group.show', compact('slider_group'));
    }


    public function edit($id)
    {
        $slider_group = SliderGroup::find($id);
        return view('admin.slider_groups.edit', compact('slider_group'));
    }


    public function update(StoreSliderGroupRequest $request, $id)
    {
        $slider_group = SliderGroup::find($id);
        $slider_group->update($request->all());
        return redirect()->route('admin::slider_groups.index')->with('status', 'Cập nhật thành công.');
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $group = SliderGroup::find($id);
            if ($group->sliders->count() > 0) {
                return response()->json(['msg' => 'Nhóm slider này đang sử dụng nội dung khác, vui lòng kiểm tra lại.', 'status' => '400']);
            } else {
                return response()->json(['msg' => 'Xóa slider thành công!', 'status' => '200']);
            }
        }
        return redirect()->route('admin::sliders.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }
}
