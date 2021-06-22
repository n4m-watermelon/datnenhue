<?php

namespace App\Http\Controllers\Admin;

use App\Events\Base\CreatedContentEvent;
use App\Events\Base\DeletedContentEvent;
use App\Events\Base\UpdatedContentEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use File;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        return redirect()->route('admin::categories.indexSpec', ['Article']);
    }

    public function indexSpec($component)
    {
        $view_folder = snake_case($component);
        switch (snake_case($component)) {
            case 'article':
                $categories = $this->category->where('component', '=', $component)->with('articles')->get();
                break;
            case 'project':
                $categories = $this->category->where('component', '=', $component)->with('projects')->get();
                break;
            case 'contact':
                $categories = $this->category->where('component', '=', $component)->with('contacts')->get();
                break;
            case 'estate':
                $categories = $this->category->where('component', '=', $component)->with('estates')->get();
                break;
            default:
                $categories = [];
                break;
        }
        return view('admin.categories.' . $view_folder . '.index', compact('categories', 'component'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return redirect()->route('admin::categories.createSpec', ['Article']);
    }

    /**
     *
     * @param $component
     * @return View
     */
    public function createSpec($component)
    {
        $view_folder = snake_case($component);
        $category = $this->category;
        return view('admin.categories.' . $view_folder . '.create', compact('category', 'component'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCategoryRequest $request
     * @return Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->all();
        // Make image
        if ($request->hasFile('image')) {
            $upload = $request->file('image');
            if (!$upload->isValid())
                return redirect()->back()->withErrors(['Việc upload ảnh đại diện thất bại, vui lòng thử lại sau.'])->withInput();
            $ext = $upload->getClientOriginalExtension();
            $newFile = str_slug($request->get('title_alias')) . '-' . time() . '.' . $ext;
            $location = public_path('upload/' . with($this->category->getImageFolder()));
            $upload->move($location, $newFile);
            $data['image'] = $newFile;
        }
        // Insert data into database
        $category = $this->category->create($data);

        event(new CreatedContentEvent( 'Category', $request, $category ));

        $moving_method = $request->input('moving_method');
        // Get parent category info
        if ($request->has('related_id')) {
            $parent = $this->category->find($request->input('related_id'));
        }
        if (isset($parent)) {
            if ($parent)
                $category->$moving_method($parent);
        }
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::categories.indexSpec', [$request->component])->with('status', trans('notices.create_success_message'));
        } else {
            return redirect()->route('admin::categories.edit', $category->id)->with('status', trans('notices.create_success_message'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $category = $this->category->find($id);
        $view_folder = snake_case($category->component);
        return view('admin.categories.' . $view_folder . '.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $category = $this->category->findOrFail($id);
        $view_folder = snake_case($category->component);
        return view('admin.categories.' . $view_folder . '.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreCategoryRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(StoreCategoryRequest $request, $id)
    {
        $data = $request->all();
        // get info category
        $category = $this->category->find($id);
        // Handel category
        $moving_method = $request->get('moving_method');
        $related_id = $request->get('related_id');
        // Get parent category info
        if ($moving_method != 'none') {
            $parent = Category::find($related_id);
            if ($category->isSelfOrAncestorOf($parent)) {
                return redirect()->back()->withErrors(['Không được phép dời một nhóm vào chính nó hoặc nhóm con !'])->withInput();
            }
            $category->$moving_method($parent);
        }
        if ($request->has('related_id'))
            if ($request->hasFile('image')) {
                $upload = $request->file('image');
                if (!$upload->isValid()) {
                    return redirect()->back()->withErrors([trans('general.ajax.not_request_ajax')])->withInput();
                }
                // Delete Image Of Categories
                if ($category->image) {
                    File::delete('upload/categories/' . $category->image);
                }
                // End Delete
                $ext = $upload->getClientOriginalExtension();
                $newFile = str_slug($request->get('title_alias')) . '-' . time() . '.' . $ext;
                $location = public_path('upload/' . with($category->getImageFolder()));
                $upload->move($location, $newFile);
                $data['image'] = $newFile;
            }
        // Update a category
        $category->update($data);
        event(new UpdatedContentEvent( 'Category', $request, $category ));
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::categories.indexSpec', [$request->component])->with('status', trans('notices.update_success_message'));
        } else {
            return redirect()->route('admin::categories.edit', $id)->with('status', trans('notices.update_success_message'));
        }
    }

    /**
     * Remove a categories
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $categories = $this->category->find($id);
            if (count($categories->children()->get()->toArray()) != 0) {
                return response()->json(['msg' => 'Nhóm này không thể xóa do có nhóm con bên trong!', 'status' => '400']);
            }
            switch ($request->get('component')) {
                case 'Project':
                    $item = $categories->projects->count();
                    break;
                case 'Article':
                    $item = $categories->articles->count();
                    break;
                case 'Contact':
                    $item = $categories->contacts->count();
                    break;
                case 'Estate':
                    $item = $categories->estates->count();
                    break;
                default:
                    return response()->json(['msg' => 'Quá trình xóa xảy ra lỗi, vui lòng thử lại sau !', 'status' => '400']);
                    break;
            }
            if ($item !== 0) {
                return response()->json(['msg' => 'Nhóm này không thể xóa do có nội dung bên trong !', 'status' => '400']);
            }
            $categories->delete();
            event(new DeletedContentEvent( 'Category', $request, $categories ));
            return response()->json(['msg' => 'Xóa thành công!', 'status' => '200']);
        }
        return response()->json(['msg' => 'Quá trình xóa xảy ra lỗi, vui lòng thử lại sau !', 'status' => '400']);
    }
}
