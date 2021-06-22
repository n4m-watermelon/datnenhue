<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartnerRequest;
use App\Repositories\Eloquent\PartnerResponsitoryInterface;
use Illuminate\Http\Request;
use Image;


class PartnersController extends Controller
{
    /**
     * @var
     */
    protected $partnerRespository;

    /**
     * PartnersController constructor.
     */
    public function __construct(PartnerResponsitoryInterface $partnerResponsitory)
    {
        $this->partnerRespository = $partnerResponsitory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partners = $this->partnerRespository->all();
        return view('admin.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $partner = new $this->partnerRespository;
        return view('admin.partners.create', compact('partner'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePartnerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePartnerRequest $request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $upload = $request->file('image');
            if (!$upload->isValid())
                return redirect()->back()->withErrors(['Việc upload ảnh đại diện thất bại, vui lòng thử lại sau.'])->withInput();
            $ext = $upload->getClientOriginalExtension();
            $file_name = time() . '-' . str_slug($request->input('title_alias')) . '.' . $ext;
            $location = public_path('upload/' . $this->partnerRespository->getImageFolder() . '/' . $file_name);
            Image::make($upload)->save($location);
            $data['image'] = $file_name;
        }
        $this->partnerRespository->create($data);
        return redirect()->route('admin::partners.index')->with('status', 'Thêm mới thành công !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $partner = new $this->partnerRespository->find($id);
        return view('admin.partners.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $partner = $this->partnerRespository->find($id);
        return view('admin.partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StorePartnerRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePartnerRequest $request, $id)
    {
        $partner = $this->partnerRespository->find($id);
        $data = $request->all();
        if ($request->hasFile('image')) {
            $upload = $request->file('image');
            if (!$upload->isValid()) {
                return redirect()->back()->withErrors(['Việc upload ảnh đại diện thất bại, vui lòng thử lại sau.'])->withInput();
            }
            $ext = $upload->getClientOriginalExtension();
            $file_name = time() . '-' . str_slug($request->get('title_alias')) . '.' . $ext;
            if ($partner->image)
                \File::delete('upload/' . $this->partnerRespository->getImageFolder() . '/' . $partner->image);

            $location = public_path('upload/' . $this->partnerRespository->getImageFolder() . '/' . $file_name);
            Image::make($upload)->save($location);
            $data['image'] = $file_name;
        }
        $this->partnerRespository->update($id, $data);
        return redirect()->route('admin::partners.index')->with('status', 'Cập nhật thành công !');
    }

    /**
     * Remove
     *
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $partner = $this->partnerRespository->delete( $id );
            if ($partner) {
                return response()->json(['msg' => 'Xóa đối tác thành công!', 'status' => '200']);
            } else {
                return response()->json(['msg' => 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.', 'status' => '400']);
            }
        }
        return redirect()->route('admin::articles.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }
}
