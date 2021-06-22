<?php

namespace App\Http\Controllers\Admin;

use App\Models\Area;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AreasController extends Controller
{

    public function index()
    {
        $areas = Area::all();
        return view('admin.areas.index', compact('areas'));
    }

    public function create()
    {
        $area = new Area;
        return view('admin.areas.create', compact('area'));
    }

    public function store(Request $request)
    {
        Area::create($request->all());
        return redirect()->route('admin::areas.index')->with('status', 'Thêm mới thành công!');
    }

    public function show($id)
    {
        $area = Area::find($id);
        return view('admin.areas.show', compact('area'));
    }

    public function edit($id)
    {
        $area = Area::find($id);
        return view('admin.areas.edit', compact('area'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $area = Area::find($id);
        $area->update($data);
        return redirect()->route('admin::areas.index')->with('status', 'Cập nhật thành công!');

    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $area = Area::find($id);
            if ($area->estates()->count() > 0) {
                return response()->json([
                    'msg' => 'Diện tích này đang sử dụng nhiều trong sản phẩm!',
                    'status' => 400], 400);
            }
            $area->delete();
            return response()->json(['msg' => 'Xóa diện tích thành công!', 'status' => 200], 200);
        }
        return redirect()->route('admin::areas.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }
}
