<?php

namespace App\Http\Controllers\Admin;

use App\Events\Base\CreatedContentEvent;
use App\Events\Base\UpdatedContentEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Estate\ImportEstateRequest;
use App\Http\Requests\StoreEstatesRequest;
use App\Models\Category;
use App\Models\District;
use App\Models\Estate;
use App\Models\EstateGallery;
use App\Models\EstateUnit;
use App\Models\Setting;
use App\Models\Street;
use App\Models\Utility;
use App\Models\Ward;
use App\Repositories\Acl\Interfaces\UserInterface;
use App\Repositories\Estate\Interfaces\EstateInterface;
use App\Services\Media\ThumbnailService;
use App\Services\Media\UploadsManager;
use App\Services\StoreEstateService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Image;
use File;
class EstatesController extends Controller
{
    /**
     * @var Estate
     */
    protected $estateRepository;
    /**
     * @var EstateGallery
     */
    protected $estateGallery;
    /**
     * @var ThumbnailService
     */
    protected $thumbnailService;
    /**
     * @var UploadsManager
     */
    protected $uploadManager;

    /**
     * EstatesController constructor.
     * @param EstateInterface $estateRepository
     * @param EstateGallery $estateGallery
     * @param ThumbnailService $thumbnailService
     * @param UploadsManager $uploadManager
     */
    public function __construct(EstateInterface $estateRepository, EstateGallery $estateGallery, ThumbnailService $thumbnailService, UploadsManager $uploadManager)
    {
        $this->thumbnailService = $thumbnailService;
        $this->uploadManager = $uploadManager;
        $this->estateRepository = $estateRepository;
        $this->estateGallery = $estateGallery;
        if (!session_id()) {
            @session_start();
            $kcfinderSession = [
                'disabled' => false,
                'uploadURL' => url('upload/'),
                'uploadDir' => ""
            ];
            $_SESSION['KCFINDER'] = $kcfinderSession;
        }
    }

    /**
     * @param UserInterface $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(UserInterface $user)
    {
        $estates = $this->estateRepository->getEstateByUser();
        if (\Auth::user()->isSuperUser()) {
            $items = $this->estateRepository->getModel()->get();
        } else {
            $items = $this->estateRepository->getModel()->where('created_by', \Auth::user()->getKey())->get();
        }
        $users = $user->getList([
            '' => 'Chọn nhân viên'
        ], []);
        return view('admin.estates.index', compact('items', 'users'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $estate = $this->estateRepository;
        $utilities = Utility::all();
        return view('admin.estates.create', compact('estate', 'utilities'));
    }

    /**
     * @param StoreEstatesRequest $request
     * @param StoreEstateService $estateService
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(StoreEstatesRequest $request, StoreEstateService $estateService)
    {
        $data = $request->input();
        $estate_areas = $request->get('estate_areas');
        $utilities = $request->get('utilities');
        $categories = $request->get('category_id');
        $estate = $this->estateRepository->createOrUpdate($data);

        if ($request->hasFile('image')) {
            $upload = $request->file('image');
            if (!$upload->isValid()) {
                return redirect()->back()->withErrors(['Việc upload ảnh đại diện thất bại, vui lòng thử lại sau.'])->withInput();
            }
            $ext = $upload->getClientOriginalExtension();
            $file_name   = Str::slug($request->input('title_alias')) . '.' . $ext;
            $path_upload = public_path('upload/' . $estate->getImageFolder() . '/' . $estate->id);
            if (!is_dir($path_upload)) {
                File::makeDirectory($path_upload, 755,true);
            }
            $path_folder = $path_upload . '/' . $file_name;
            $img = Image::make($upload);
            $watermark = Image::make(public_path('upload/settings/' . Setting::getSetting('site')->watermark))->widen(floor($img->width() / 3), function ($constraint) {
                $constraint->upsize();
            });
            $img->insert($watermark, 'bottom-right', floor($img->width() / 100), floor($img->width() / 100))->save($path_folder);
            $estate->image = $file_name;
            $estate->save();
            if (\File::exists($path_folder)) {
                if (is_image($this->uploadManager->fileMimeType($path_folder))) {
                    foreach (config('media.sizes') as $size) {
                        $readable_size = explode('x', $size);
                        $this->thumbnailService
                            ->setImage($upload->getRealPath())
                            ->setSize($readable_size[0], $readable_size[1])
                            ->setDestinationPath($estate->getImageFolder() . '/' . $estate->id)
                            ->setFileName(\File::name($file_name) . '-' . $size . '.' . $ext)
                            ->save();
                    }
                }
            }
        }


        event(new CreatedContentEvent(get_class($this->estateRepository->getModel()), $request, $estate));

        $estateService->execute($request, $estate);

        if ($request->hasFile('gallery')) {
            $gallery = $request->file('gallery');
            foreach ($gallery as $k => $gallery_img) {
                $extFile = $gallery_img->getClientOriginalExtension();
                $newFileUpload = Str::slug($request->input('title_alias')) . '-' . $k . '-' . time() . '.' . $extFile;
                $gallery_img->move('upload/estate-galleries/', $newFileUpload);
                EstateGallery::create([
                    'name' => $newFileUpload,
                    'estate_id' => $estate->id
                ]);
            }
        }
        if (!empty($categories)) {
            $estate->categories()->attach($categories);
        }
        // Add tagging for estate
        if ($request->get('hash_tag')) {
            $estate->tag($request->get('hash_tag'));
        }
        if (!empty($estate_areas)) {
            $estate->areas()->attach($estate_areas);
        }
        if (!empty($utilities)) {
            $estate->utilities()->attach($utilities);
        }
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::estates.index')->with('status', trans('notices.create_success_message'));
        } else {
            return redirect()->route('admin::estates.edit', $estate->id)->with('status', trans('notices.create_success_message'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $estate = Estate::find($id);
        return view('admin.estates.show', compact('estate'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $estate = $this->estateRepository->findOrFail($id);
        $districts = District::getList([], [], $estate->province_id);
        $wards = Ward::getList([], [], $estate->district_id);
        $streets = Street::getList([], [], $estate->district_id);
        $units = EstateUnit::getList([], [], $estate->type_id);
        $utilities = Utility::all();
        $utilitiesSelect = [];
        foreach ($estate->utilities as $utility) {
            $utilitiesSelect[] = $utility->id;
        }
        \Event::dispatch('estate.editing', [$estate]);
        return view('admin.estates.edit', compact('estate', 'districts', 'wards', 'streets', 'units', 'utilities', 'utilitiesSelect'));
    }

    /**
     * @param StoreEstatesRequest $request
     * @param $id
     * @param StoreEstateService $estateService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreEstatesRequest $request, $id, StoreEstateService $estateService)
    {
        try{
            $estate = $this->estateRepository->findOrFail($id);
            $utilities = $request->get('utilities');
            $estate->fill($request->input());
            $this->estateRepository->createOrUpdate($estate);
            if ($request->hasFile('image')) {
                $upload = $request->file('image');
                if (!$upload->isValid()) {
                    return redirect()->back()->withErrors(['Việc upload ảnh đại diện thất bại, vui lòng thử lại sau.'])->withInput();
                }
                $ext = $upload->getClientOriginalExtension();
                $file_name = Str::slug($request->get('title_alias')) . '.' . $ext;
                $path_upload = public_path('upload/' . $estate->getImageFolder() . '/' . $estate->id);
                if (!is_dir($path_upload)) {
                    File::makeDirectory($path_upload, 755,true);
                }
                if (empty($estate->image) && \File::exists($estate->getImagePath())) {
                    \File::delete($estate->getImagePath());
                }
                $path_folder = $path_upload. '/' . $file_name;
                $img = Image::make($upload);
                $watermark = Image::make(public_path('upload/settings/' . Setting::getSetting('site')->watermark))->widen(floor($img->width() / 3), function ($constraint) {
                    $constraint->upsize();
                });
                $img->insert($watermark, 'bottom-right', floor($img->width() / 100), floor($img->width() / 100))->save($path_folder);
                $estate->image = $file_name;
                $estate->save();
            }
            if ($request->hasFile('image')) {
                if (\File::exists('upload/' . $estate->getImageFolder() . '/' . $estate->id . '/' . $estate->image)) {
                    if (is_image($this->uploadManager->fileMimeType('upload/' . $estate->getImageFolder() . '/' . $estate->id . '/' . $estate->image))) {
                        foreach (config('media.sizes') as $size) {
                            $readable_size = explode('x', $size);
                            $this->thumbnailService->setImage($upload->getRealPath())->setSize($readable_size[0],
                                    $readable_size[1])->setDestinationPath($estate->getImageFolder() . '/' . $estate->id)->setFileName(\File::name($file_name) . '-' . $size . '.' . $ext)->save()
                            ;
                        }
                    }
                }
            }
            event(new UpdatedContentEvent(get_class($this->estateRepository->getModel()), $request, $estate));
            $estateService->execute($request, $estate);

            if (!empty($utilities)) {
                $estate->utilities()->sync($utilities);
            }
            if ($request->hasFile('gallery')) {
                $gallery = $request->file('gallery');
                foreach ($gallery as $k => $gallery_img) {
                    $extFile = $gallery_img->getClientOriginalExtension();
                    $newFileUpload = Str::slug($request->input('title_alias')) . '-' . $k . '-' . time() . '.' . $extFile;
                    $gallery_img->move('upload/estate-galleries/', $newFileUpload);
                    EstateGallery::create([
                        'name'       => $newFileUpload,
                        'estate_id'  => $estate->id
                    ]);
                }
            }
            // Article tag
            if ($request->get('hash_tag'))
                $estate->retag($request->get('hash_tag'));
            else
                $estate->untag();

            if ($request->get('submit') == 'save') {
                return redirect()->route('admin::estates.index')->with('status', trans('notices.update_success_message'));
            } else {
                return redirect()->route('admin::estates.edit', $id)->with('status', trans('notices.update_success_message'));
            }
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }
    }

    /**
     *
     * EstatesController::destroy()
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $estate = $this->estateRepository->findById($id);
            $this->estateRepository->delete($estate);
            return response()->json([
                'msg'    => 'Xóa bất động sản thành công!',
                'status' => 200
            ], 200);
        }
        return redirect()->route('admin::estates.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function getCategoriesByTypeId(Request $request)
    {
        if ($request->ajax()) {
            $type_id = $request->get('type_id');
            return response()->json([
                'data' => [
                    'categories' => Category::getList([
                        'component' => 'Estate',
                        'type_id' => $type_id
                    ], []),
                    'units' => EstateUnit::where('type', $type_id)->get(['id', 'title', 'type'])
                ],
                'msg' => '',
                'status' => 200
            ], 200);
        }
        return redirect()->route('admin::estates.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function getDistrictByProvinceId(Request $request)
    {
        if ($request->ajax()) {
            $province_id = $request->get('province_id');
            $districts = District::where('province_id', $province_id)->get();
            if ($districts->count() > 0) {
                return response()->json([
                    'data' => $districts,
                    'msg' => '',
                    'status' => 200
                ], 200);
            } else {
                return response()->json([
                    'data' => '',
                    'msg' => 'Empty Data',
                    'status' => 400
                ], 400);
            }
        }
        return redirect()->route('admin::estates.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function getWardsAndStreets(Request $request)
    {

        if ($request->ajax()) {
            $district_id = $request->get('district_id');
            return response()->json([
                'data' => [
                    'wards' => Ward::where('district_id', $district_id)->get(),
                    'streets' => Street::where('district_id', $district_id)->get()
                ],
                'msg' => '',
                'status' => 200
            ], 200);
        }
        return redirect()->route('admin::estates.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }

    public function delImage(Request $request, $id)
    {
        if ($request->ajax()) {
            $image = EstateGallery::findOrFail($id);
            if (\File::exists('upload/estate-galleries/' . $image->name)) {
                \File::delete('upload/estate-galleries/' . $image->name);
            }
            $image->delete();
            return response()->json(['msg' => 'Xóa ảnh thành công!', 'status' => 200]);
        }
        return redirect()->route('admin::tours.index')->with('error', trans('general.ajax.not_request_ajax'));
    }

    /**
     * @param Request $request
     * @param $id
     * @param $value
     * @param $field
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function change(Request $request, $id, $value, $field)
    {
        if ($request->ajax()) {
            $estate = Estate::find($id);
            $estate->update([$field => $value]);
            if ($estate) {
                return response()->json([
                    'status' => 200,
                    'msg' => 'Cập nhật thông tin thành công!'
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'msg' => 'Đã có lỗi trong quá trình xử lý.'
                ]);
            }
        }
        return redirect()->route('admin::tours.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }


    /**
     *
     * Update name for image
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function titleImage(Request $request, $id)
    {
        if ($request->ajax()) {
            $image = $this->estateGallery->findOrFail($id);
            $image->title = $request->get('title');
            $debug = $image->update();
            if ($debug) {
                return response()->json([
                    'status' => 200,
                    'msg' => 'Cập nhật tiêu đề'
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'msg' => 'Quá trình xử lý bị lỗi, vui lòng thử lại sau !'

                ]);
            }
        }
        return redirect()->route('admin::tours.index')->with('error', trans('general.ajax.not_request_ajax'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getImportExcel()
    {
        return view('admin.estates.import');
    }

    /**
     * @param ImportEstateRequest $request
     */
    public function postImportExcel(ImportEstateRequest $request)
    {
        try {
            $import_file = $request->file('import_file');
            $path = $import_file->getRealPath();
            $data = \Excel::load($path)->get();
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }


    /**
     * Download File
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadExample()
    {
        $file = public_path('upload/examples/Example_Client.xlsx');
        if (!\File::exists($file)) {
            return redirect()->back()->withErrors('File not exists');
        }
        return response()->download($file);
    }


    public function fixImage()
    {
        try {
            $data = $this->estateRepository->all();
            if ($data) {
                foreach ($data as $item) {
                    if ($item->image) {
                        $imagePath = public_path('upload/' . $item->getImageFolder() . '/' . $item->image);
                        /*$ext = pathinfo($imagePath, PATHINFO_EXTENSION);
                        $img = Image::make($imagePath);
                        $watermark = Image::make(public_path('upload/settings/' . Setting::getSetting('site')->watermark))->widen(floor($img->width() / 3), function ($constraint) {
                            $constraint->upsize();
                        });
                        $img->insert($watermark, 'bottom-right', floor($img->width() / 100), floor($img->width() / 100))->save($imagePath);*/
                        \File::move($imagePath, public_path('upload/' . $item->getImageFolder() . '/' . $item->id . '/' . $item->image));

                        /*if (\File::exists($imagePath)) {
                            $file = new Filesystem;
                            $file->cleanDirectory('upload/'.$item->getImageFolder().'/' . $item->id);
                            if (is_image($this->uploadManager->fileMimeType($item->getImagePath()))) {
                                foreach (config('media.sizes') as $size) {
                                    $readable_size = explode('x', $size);
                                    $this->thumbnailService
                                        ->setImage($imagePath)
                                        ->setSize($readable_size[0], $readable_size[1])
                                        ->setDestinationPath($item->getImageFolder().'/' . $item->id)
                                        ->setFileName(\File::name($item->image) . '-' . $size . '.' . $ext)
                                        ->save();
                                }
                            }
                        }*/
                    }

                }
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }

}
