<?php

namespace App\Http\Controllers\Admin;

use App\Models\EstateDirection;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstateDirectionRequest;
use Illuminate\Http\Request;

class EstatesDirectionsController extends Controller
{

    public function index()
    {
        $directions = EstateDirection::all();
        return view('admin.directions.index', compact('directions'));
    }

    public function create()
    {
        $direction = new EstateDirection;
        return view('admin.directions.create', compact('direction'));
    }

    public function store(StoreEstateDirectionRequest $request)
    {
        $data = $request->all();
        EstateDirection::create($data);
        return redirect()->route('admin::directions.create')->with('status', 'Thêm mới thành công!');
    }

    public function show($id)
    {
        $direction = EstateDirection::findOrFail($id);
        return view('admin.directions.show', compact('direction'));
    }

    public function edit($id)
    {
        $direction = EstateDirection::findOrFail($id);
        return view('admin.directions.edit', compact('direction'));
    }

    public function update(StoreEstateDirectionRequest $request, $id)
    {
        $direction = EstateDirection::findOrFail($id);
        $data = $request->all();
        $direction->update($data);
        return redirect()->route('admin::directions.index')->with('status', 'Cập nhật thành công!');
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $direction = EstateDirection::find($id);
            $direction->delete();
            return response()->json([
                'msg' => 'Xóa hướng bất động sản thành công!',
                'status' => 200
            ], 200);
        }
        return redirect()->route('admin::directions.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }
}
