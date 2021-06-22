<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuType;
use Illuminate\Http\Request;

class MenuTypesController extends Controller
{
    public function index()
    {
        $types = MenuType::all();
        return view('admin.menu_items.index', compact('types'));
    }

    public function create()
    {
        $type = new MenuType;
        return view('admin.menu_items.create', compact('type'));
    }

    public function store(Request $request)
    {

    }

    public function show($id)
    {
        $type = MenuType::find($id);
        return view('admin.menu_items.show', compact('type'));
    }

    public function edit($id)
    {
        $type = MenuType::find($id);
        return view('admin.menu_items.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function getDataList($id)
    {
        $menutype = MenuType::find($id);
        if (!empty($menutype->get_data_code)) {
            eval('$data = ' . $menutype->get_data_code);
        } else {
            $data = [];
        }
        if (\Request::ajax()) {
            $list = null;
            foreach ($data as $key => $title) {
                $list .= '<option value="' . $key . '">' . $title . '</option>';
            }
            return $list;
        }
        return $data;
    }
}
