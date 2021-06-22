<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStreetRequest;
use App\Models\Street;
use Illuminate\Http\Request;

class StreetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $streets = Street::whereHas('district', function ($query) {
            $query->whereHas('province', function ($query) {
                $query->where('province_id', 1);
            });
        })->orderBy('district_id')->paginate(60);
        return view('admin.streets.index', compact('streets'));
    }

    public function create()
    {
        $street = new Street;
        return view('admin.streets.create', compact('street'));
    }

    public function store(StoreStreetRequest $request)
    {
        $data = $request->all();
        $data['street_slug'] = str_slug($data['name']);
        Street::create($data);
        return redirect()->route('admin::streets.index')->with('status', 'Thêm mới thành công!');
    }

    public function show($id)
    {
        $street = Street::findOrFail($id);
        return view('admin.streets.show', compact('street'));
    }


    public function edit($id)
    {
        $street = Street::findOrFail($id);
        return view('admin.streets.edit', compact('street'));
    }

    public function update(StoreStreetRequest $request, $id)
    {
        $street = Street::find($id);
        $data = $request->all();
        $data['street_slug'] = str_slug($request->get('name'));
        $street->update($data);
        return redirect()->route('admin::streets.index')->with('status', 'Chỉnh sửa thành công!');
    }

    public function destroy($id)
    {
        //
    }
}
