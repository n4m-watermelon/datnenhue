<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Models\Page;
use Illuminate\Http\Request;


class PagesController extends Controller
{

    public function __construct()
    {
        if (!session_id()) {
            session_start();
            $kcfinderSession = [
                'disabled' => false,
                'uploadURL' => url('public/upload/'),
                'uploadDir' => ""
            ];
            $_SESSION['KCFINDER'] = $kcfinderSession;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = new Page;
        return view('admin.pages.create', compact('page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePageRequest $request)
    {
        $data = $request->all();


        return redirect()->route('admin::pages.index')->with('status', 'Thêm mới thành công !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Page::find($id);
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StorePageRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePageRequest $request, $id)
    {
        $data = $request->all();

        return redirect()->route('admin::pages.index')->with('status', 'Cập nhật thành công !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {

        }
        return redirect()->route('admin::pages.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }
}
