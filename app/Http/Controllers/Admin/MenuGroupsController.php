<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuGroupsRequest;
use App\Models\MenuGroup;
use Illuminate\Http\Request;

class MenuGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu_groups = MenuGroup::all();
        return view('admin.menu_groups.index', compact('menu_groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menu_group = new MenuGroup;
        return view('admin.menu_groups.create', compact('menu_group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreMenuGroupsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMenuGroupsRequest $request)
    {
        $data = $request->all();
        $data['title_alias'] = str_slug($request->get('title'));
        $menu_group = MenuGroup::create($data);
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::menu_groups.index')->with('status', trans('notices.create_success_message'));
        } else {
            return redirect()->route('admin::menu_groups.edit', $menu_group->id)->with('status', trans('notices.create_success_message'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menu_group = MenuGroup::find($id);
        return view('admin.menu_groups.show', compact('menu_group'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu_group = MenuGroup::find($id);
        return view('admin.menu_groups.edit', compact('menu_group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreMenuGroupsRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreMenuGroupsRequest $request, $id)
    {
        $menu_group = MenuGroup::find($id);
        $menu_group->title = $request->get('title');
        $menu_group->title_alias = str_slug($request->get('title'));
        $menu_group->description = $request->get('description');
        $menu_group->save();
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::menu_groups.index')->with('status', trans('notices.update_success_message'));
        } else {
            return redirect()->route('admin::menu_groups.edit', $id)->with('status', trans('notices.update_success_message'));
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $menu_group = MenuGroup::findOrFail($id);
            $menu_group->delete();
            return response()->json([
                'msg' => trans('notices.delete_success_message'),
                'status' => '200'
            ]);
        }
        return redirect()->route('admin::menu_groups.index')->with('error', trans('notices.error_action'));
    }
}
