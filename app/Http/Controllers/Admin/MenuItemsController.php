<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuItemsRequest;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemsController extends Controller
{

    public function index()
    {
        $menu_items = MenuItem::all();
        return view('admin.menu_items.index', compact('menu_items'));
    }

    public function create()
    {
        $menu_item = new MenuItem;
        return view('admin.menu_items.create', compact('menu_item'));
    }

    public function store(StoreMenuItemsRequest $request)
    {
        $data = $request->all();
        $menu_item = MenuItem::create($data);
        $moving_method = $request->get('moving_method');
        // Get parent category info
        if ($request->has('related_id')) {
            $parent = MenuItem::find($request->get('related_id'));
        }
        if (isset($parent)) {
            if ($parent)
                $menu_item->$moving_method($parent);
        }
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::menu_items.index')->with('status', trans('notices.create_success_message'));
        } else {
            return redirect()->route('admin::menu_items.edit', $menu_item->id)->with('status', trans('notices.create_success_message'));
        }
    }

    public function show($id)
    {
        $menu_item = MenuItem::find($id);
        return view('admin.menu_items.show', compact('menu_item'));
    }

    public function edit($id)
    {
        $menu_item = MenuItem::findOrFail($id);
        if (is_null($menu_item)) {
            return redirect()->route('menu_items.index')->withErrors('Đã có lỗi xảy ra, vui lòng thử lại sau ít phút');
        }
        if (!empty($menu_item->type_id)) {
            $getDataController = new MenuTypesController;
            $data = $getDataController->getDataList($menu_item->type_id);
        } else {
            $data = [];
        }
        return view('admin.menu_items.edit', compact('menu_item', 'data'));
    }

    public function update(StoreMenuItemsRequest $request, $id)
    {
        $data = $request->all();
        $menu_item = MenuItem::find($id);
        $moving_method = $request->get('moving_method');
        $related_id = $request->get('related_id');
        if ($moving_method != 'none') {
            $parent = MenuItem::find($related_id);
            if (isset($parent)) {
                if ($menu_item->isSelfOrAncestorOf($parent)) {
                    return redirect()->back()->withErrors(['Không được phép dời một trình đơn vào chính nó hoặc nhóm con'])->withInput();
                }
                $menu_item->$moving_method($parent);
            }
        }
        $menu_item->update($data);
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::menu_items.index')->with('status', trans('notices.update_success_message'));
        } else {
            return redirect()->route('admin::menu_items.edit', $id)->with('status', trans('notices.update_success_message'));
        }
    }

    public function getItemList(Request $request, $id)
    {
        if ($request->ajax()) {
            $items = MenuItem::getList(['group_id' => $id], [0 => 'Cấp liên kết cao nhất']);
            $list = null;
            foreach ($items as $key => $item) {
                $list .= '<option value="' . $key . '">' . $item . '</option>';
            }
            return $list;
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $menu_item = MenuItem::findOrFail($id);
            // Determine if this menu item has sub menu item
            if (count($menu_item->children()->get()->toArray()) != 0) {
                if ($request->ajax()) {
                    return response()->json(['msg' => 'Liên kết này không thể xóa do có liên kết con bên trong.', 'status' => 400]);
                }
                return redirect()->route('admin::menu_items.index')->with('status', 'Liên kết này không thể xóa do có liên kết con bên trong');
            }
            $menu_item->delete();
            return response()->json(['msg' => 'Xóa trình đơn thành công!', 'status' => 200]);
        }
        return redirect()->route('admin::blocks.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }
}
