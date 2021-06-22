<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWardRequest;
use App\Models\Ward;

class WardsController extends Controller
{

    public function index()
    {
        $wards = Ward::whereHas('district', function ($query) {
            $query->where('province_id', 33);
            $query->where('district_id', 349);
        })->get();
        return view('admin.wards.index', compact('wards'));
    }


    public function create()
    {
        $ward = new Ward;
        return view('admin.wards.create', compact('ward'));
    }


    public function store(StoreWardRequest $request)
    {
        $data = $request->all();
        $data['title_alias'] = str_slug($data['name']);
        Ward::create($data);
        return redirect()->route('admin::wards.index')->with('status', 'Thêm mới thành công!');
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $ward = Ward::find($id);
        return view('admin.wards.edit', compact('ward'));
    }


    public function update(StoreWardRequest $request, $id)
    {
        $data = $request->all();
        $ward = Ward::find($id);
        $data['title_alias'] = str_slug($data['name']);
        $ward->update($data);
        return redirect()->route('admin::wards.index')->with('status', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        //
    }
}
