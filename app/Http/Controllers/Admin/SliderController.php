<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSlidersRequest;
use App\Repositories\Eloquent\SliderResponsitoryInterface;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * @var SliderResponsitoryInterface
     */
    protected $sliderResponsitory;

    /**
     * SliderController constructor.
     * @param SliderResponsitoryInterface $sliderResponsitory
     */
    public function __construct(SliderResponsitoryInterface $sliderResponsitory)
    {
        $this->sliderResponsitory = $sliderResponsitory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = $this->sliderResponsitory->all();
        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $slider = new $this->sliderResponsitory;
        return view('admin.sliders.create', compact('slider'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreSlidersRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSlidersRequest $request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $upload = $request->file('image');
            if (!$upload->isValid())
                return redirect()->back()->withErrors(['Việc upload ảnh đại diện thất bại, vui lòng thử lại sau.'])->withInput();
            $ext = $upload->getClientOriginalExtension();
            $file_name = time() . '-' . str_slug($data['params']['title']) . '.' . $ext;
            // Save path : storage/images/...
            $upload->move(public_path('upload/' . $this->sliderResponsitory->getImageFolder()), $file_name);
            $data['image'] = $file_name;
        }
        $slider = $this->sliderResponsitory->create($data);
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::sliders.index')->with('status', trans('notices.create_success_message'));
        } else {
            return redirect()->route('admin::sliders.edit', $slider->id)->with('status', trans('notices.create_success_message'));
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
        $slider = $this->sliderResponsitory->find($id);
        return view('admin.sliders.show', 'slider');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slider = $this->sliderResponsitory->find($id);
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified
     *
     * @param  StoreSlidersRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSlidersRequest $request, $id)
    {
        $slider = $this->sliderResponsitory->find($id);
        $data = $request->all();
        if ($request->hasFile('image')) {
            $upload = $request->file('image');
            if (!$upload->isValid())
                return redirect()->back()->withErrors(['Việc upload ảnh đại diện thất bại, vui lòng thử lại sau.'])->withInput();
            if (\File::exists('upload/' . $this->sliderResponsitory->getImageFolder() . '/' . $slider->image)) {
                \File::delete('upload/' . $this->sliderResponsitory->getImageFolder() . '/' . $slider->image);
            }
            $ext = $upload->getClientOriginalExtension();
            $file_name = time() . '-' . str_slug($data['params']['title']) . '.' . $ext;
            $upload->move('upload/' . $this->sliderResponsitory->getImageFolder() . '/', $file_name);
            $data['image'] = $file_name;
        }
        $slider->update($data);
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::sliders.index')->with('status', trans('notices.update_success_message'));
        } else {
            return redirect()->route('admin::sliders.edit', $id)->with('status', trans('notices.update_success_message'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            if ($this->sliderResponsitory->delete($id))
                return response()->json(['msg' => 'Xóa slider thành công!', 'status' => '200']);
            else
                return response()->json(['msg' => 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.', 'status' => '400']);
        }
        return redirect()->route('admin::sliders.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }
}
