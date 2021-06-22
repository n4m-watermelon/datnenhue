<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Traits\ProjectGetSettingTrait;
use App\Models\Project;
use App\Repositories\Eloquent\ProjectRespositoryInterface;
use File;
use Illuminate\Http\Request;
use Image;

/**
 * Class ProjectsController
 *
 * @package App\Http\Controllers\Admin
 */
class ProjectsController extends Controller
{
    use ProjectGetSettingTrait;

    protected $projectResponsitory;

    public function __construct(ProjectRespositoryInterface $projectRespository)
    {
        $this->projectResponsitory = $projectRespository;
        if (!session_id()) {
            session_start();
            $kcfinderSession = [
                'disabled'  => false,
                'uploadURL' => url('upload/' . $this->projectResponsitory->getImageFolder()),
                'uploadDir' => ""
            ];
            $_SESSION['KCFINDER'] = $kcfinderSession;
        }
    }

    public function index()
    {
        $projects = $this->projectResponsitory->all();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $project = new $this->projectResponsitory;
        return view('admin.projects.create', compact('project'));
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->all();
        // has input image
        if ($request->hasFile('image')) {
            $upload = $request->file('image');
            if (!$upload->isValid()) {
                return redirect()->back()->withErrors(['Việc upload ảnh đại diện thất bại, vui lòng thử lại sau.'])->withInput();
            }
            $ext = $upload->extension();
            $file_name = str_slug($request->get('title_alias')) . '-' . time() . '.' . $ext;
            $location = public_path('upload/' . $this->projectResponsitory->getImageFolder() . '/' . $file_name);
            Image::make($upload)->save($location);
            $data['image'] = $file_name;
        }
        // Create a project to database
        $project = $this->projectResponsitory->create($data);
        if ($request->input('hash_tags')) {
            $project->tag($request->input('hash_tags'));
        }
        return redirect()->route('admin::projects.index')->with('status', 'Thêm mới thành công!');
    }

    public function show($id)
    {
        $project = $this->projectResponsitory->find($id);
        return view('admin.projects.show', compact('project'));
    }

    public function edit($id)
    {
        $project = $this->projectResponsitory->find($id);
        return view('admin.projects.edit', compact('project'));
    }

    public function update(StoreProjectRequest $request, $id)
    {
        $project = $this->projectResponsitory->find($id);
        $data = $request->all();
        // Has Image
        if ($request->hasFile('image')) {
            $upload = $request->file('image');
            if (!$upload->isValid()) {
                return redirect()->back()->withErrors(['Việc upload ảnh đại diện thất bại, vui lòng thử lại sau.'])->withInput();
            }
            $ext = $upload->extension();
            $file_name = str_slug($request->get('title_alias')) . '-' . time() . '.' . $ext;
            if ($project->image) {
                if (File::exists('upload/' . $this->projectResponsitory->getImageFolder() . '/' . $project->image)) {
                    File::delete('upload/' . $this->projectResponsitory->getImageFolder() . '/' . $project->image);
                }
            }
            $location = public_path('upload/' . $this->projectResponsitory->getImageFolder() . '/' . $file_name);
            Image::make($upload)->save($location);
            $data['image'] = $file_name;
        }
        $this->projectResponsitory->update($id, $data);
        return redirect()->route('admin::projects.index')->with('status', 'Cập nhật thành công!');
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $project = Project::with('tagged')->find($id);
            $project->delete($request->all());
            return response()->json(['msg' => 'Xóa dự án thành công!', 'status' =>200], 200);
        }
        return redirect()->route('admin::projects.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }
}
