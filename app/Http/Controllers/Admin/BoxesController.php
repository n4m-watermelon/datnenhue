<?php

namespace App\Http\Controllers\Admin;

use App\Models\Box;
use App\Http\Controllers\Controller;
use App\Http\Requests\BoxStoreRequest;
use Illuminate\Http\Request;

class BoxesController extends Controller
{
    protected $box;

    public function __construct(Box $box)
    {
        $this->box = $box;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $boxes = $this->box->get();
        //
        return view('admin.boxes.index', compact('boxes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $box = $this->box;
        return view('admin.boxes.create', compact('box'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BoxStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BoxStoreRequest $request)
    {
        $data = $request->all();
        //
        if ($request->hasFile('image')) {
            $upload = $request->file('image');
            if (!$upload->isValid())
                return redirect()->back()->withErrors(['Việc upload ảnh đại diện thất bại, vui lòng thử lại sau.'])->withInput();
            $ext = $upload->getClientOriginalExtension();
            $file_name = time() . '-' . str_slug($request->get('title_alias')) . '.' . $ext;
            $location = public_path('upload/' . $this->box->getImageFolder() . '/');
            $upload->move($location, $file_name);
            $data['image'] = $file_name;
        }

        $this->box->create($data);

        return redirect()->route('admin::boxes.index')->with('status', trans('notices.create_success_message'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $box = $this->box->find($id);
        return view('admin.boxes.show', compact('box'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $box = $this->box->find($id);
        return view('admin.boxes.edit', compact('box'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BoxStoreRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BoxStoreRequest $request, $id)
    {
        $box = $this->box->find($id);
        if (!$box)
            return view('admin.boxes.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
        //
        $data = $request->all();
        if ($request->hasFile('image')) {
            $upload = $request->file('image');
            if (!$upload->isValid()) {
                return redirect()->back()->withErrors(['Việc upload ảnh đại diện thất bại, vui lòng thử lại sau.'])->withInput();
            }
            $ext = $upload->getClientOriginalExtension();
            $file_name = time() . '-' . str_slug($request->get('title_alias')) . '.' . $ext;
            if (!is_null($box->image) && \File::exists('upload/' . $this->box->getImageFolder() . '/' . $box->image)) {
                \File::delete('upload/' . $this->box->getImageFolder() . '/' . $box->image);
            }
            $location = public_path('upload/' . $this->box->getImageFolder());
            $upload->move($location, $file_name);
            $data['image'] = $file_name;
        }
        $box->update($data);
        return redirect()->route('admin::boxes.index')->with('status', trans('notices.update_success_message'));
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
            $box = $this->box->find($id);
            $box->delete();
            return response()->json([
                'msg' => trans('notices.delete_success_message'),
                'status' => 200
            ], 200);
        }
        //
        return redirect()->route('admin::boxes.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }
}
