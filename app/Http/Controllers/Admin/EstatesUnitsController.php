<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstateUnitRequest;
use App\Models\EstateUnit;
use Illuminate\Http\Request;

class EstatesUnitsController extends Controller
{

    public function index()
    {
        $units = EstateUnit::all();
        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        $unit = new EstateUnit;
        return view('admin.units.create', compact('unit'));
    }

    public function store(StoreEstateUnitRequest $request)
    {
        $data = $request->all();
        EstateUnit::create($data);
        return redirect()->route('admin::units.index')->with('status', 'Thêm mới Đơn Vị thành công!');
    }


    public function show($id)
    {
        $unit = EstateUnit::findOrFail($id);
        return view('admin.units.show', compact('unit'));
    }

    public function edit($id)
    {
        $unit = EstateUnit::findOrFail($id);
        return view('admin.units.edit', compact('unit'));
    }

    public function update(StoreEstateUnitRequest $request, $id)
    {
        $unit = EstateUnit::find($id);
        $data = $request->all();
        $unit->update($data);
        return redirect()->route('admin::units.index')->with('status', 'Cập nhật đơn vị thành công !');

    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $unit = EstateUnit::find($id);
            $unit->delete();
            return response()->json([
                'msg' => 'Xóa đơn vị bất động sản thành công!',
                'status' => 200
            ]);
        }
        return redirect()->route('admin::units.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }
}
