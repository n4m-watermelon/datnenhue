<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDistrictRequest;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DistrictsController extends Controller
{

    public function index()
    {
        $districts = District::where('province_id', 57)->orderByDesc('id')->get();
        return view('admin.districts.index', compact('districts'));
    }

    public function create()
    {
        $district = new District;
        return view('admin.districts.create', compact('district'));
    }

    public function store(StoreDistrictRequest $request)
    {
        $data = $request->all();
        $data['province_id'] = $request->get('province_id');
        $data['slug_name'] = str_slug($data['name']);
        District::create($data);
        return redirect()->route('admin::districts.index')->with('status', 'Thêm mới thành công!');
    }

    public function show($id)
    {
        $district = District::find($id);
        return view('admin.districts.show', compact('district'));
    }

    public function edit($id)
    {
        $district = District::find($id);
        return view('admin.districts.edit', compact('district'));
    }

    public function update(StoreDistrictRequest $request, $id)
    {
        $district = District::where('province_id', $request->get('province_id'))->where('id', $id)->firstOrFail();
        $data = $request->all();
        $data['province_id'] = $request->get('province_id');
        if ($request->hasFile('image')) {
            $upload = $request->file('image');
            if (!$upload->isValid()) {
                return redirect()->back()->withErrors([trans('general.ajax.not_request_ajax')])->withInput();
            }
            // Delete Image Of Categories
            if ($district->image) {
                \File::delete('upload/' . $district->getImagePath());
            }
            // End Delete
            $ext = $upload->getClientOriginalExtension();
            $newFile = str_slug($request->get('name')) . '-' . time() . '.' . $ext;
            $location = public_path('upload/' . with($district->getImageFolder()));
            $upload->move($location, $newFile);
            $data['image'] = $newFile;
        }
        $district->update($data);
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::districts.index')->with('status', trans('notices.update_success_message'));
        } else {
            return redirect()->route('admin::districts.edit', $id)->with('status', trans('notices.update_success_message'));
        }
    }

    public function destroy(Request $request, $id)
    {
        //
    }
}
