<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jackiedo\LogReader\LogReader;

class LogReaderController extends Controller
{

    protected $reader;

    public function __construct(LogReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reader = $this->reader->orderBy('date', 'desc')->get();
        return view('admin.logs.index', compact('reader'));
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        echo "string";
    }

    /**
     *
     * LogReaderController::deleteAll()
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAll()
    {
        $this->reader->delete();
        return redirect()->route('admin::logs.index')->with('status', 'Xóa nhật ký ghi lỗi thành công !');
    }
}
