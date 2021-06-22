<?php

namespace App\Http\Controllers\Admin;

use App\Events\ACL\RoleAssignmentEvent;
use App\Events\ACL\RoleUpdateEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleCreateRequest;
use App\Repositories\Acl\Interfaces\RoleInterface;
use App\Repositories\Acl\Interfaces\UserInterface;
use Illuminate\Http\Request;
use App\Supports\Http\Responses\BaseHttpResponse;
use Artisan;

class RolesController extends Controller
{
    /**
     * @var RoleInterface
     */
    protected $roleRepository;

    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * RoleController constructor.
     * @param RoleInterface $roleRepository
     * @param UserInterface $userRepository
     */
    public function __construct(RoleInterface $roleRepository, UserInterface $userRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
        $this->middleware('auth', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->roleRepository->all();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role  = $this->roleRepository;
        $flags = $this->getAvailablePermissions();
        $children = $this->getPermissionTree($flags);
        $active = [];
        return view('admin.roles.create', compact('role', 'flags', 'children','active'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RoleCreateRequest $request)
    {
        try {
            $role = $this->roleRepository->createOrUpdate([
                'name' => $request->input('name'),
                'slug'        => $this->roleRepository->createSlug($request->input('name'), 0),
                'permissions' => $this->cleanPermission($request->input('flags')),
                'description' => $request->input('description'),
                'is_default'  => $request->input('is_default') !== null ? 1 : 0,
                'created_by' => auth()->user()->getKey(),
                'updated_by' => auth()->user()->getKey(),
            ]);

            if ($request->get('submit') == 'save') {
                return redirect()->route('admin::roles.index')->with('status', trans('notices.create_success_message'));
            } else {
                return redirect()->route('admin::roles.edit', $role->id)->with('status', trans('notices.create_success_message'));
            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        //
    }

    /**
     * Return a correctly type casted permissions array
     * @param array $permissions
     * @return array
     */
    protected function cleanPermission($permissions)
    {
        if (!$permissions) {
            return [];
        }

        $cleanedPermissions = [];
        foreach ($permissions as $permissionName) {
            $cleanedPermissions[$permissionName] = true;
        }

        return $cleanedPermissions;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = $this->roleRepository->findOrFail($id);
        $flags = $this->getAvailablePermissions();
        $children = $this->getPermissionTree($flags);
        $active = [];
        if ($role->getModel()) {
            $active = array_keys($role->getModel()->permissions);
        }
        return view('admin.roles.edit', compact('role', 'flags', 'active','children'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RoleCreateRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleCreateRequest $request, $id)
    {
        try{
            $role = $this->roleRepository->findOrFail($id);
            $role->name = $request->input('name');
            $role->permissions = $this->cleanPermission($request->input('flags'));
            $role->description = $request->input('description');
            $role->updated_by  = auth()->user()->getKey();
            $role->is_default  = $request->input('is_default', 0);
            $this->roleRepository->createOrUpdate($role);
            Artisan::call('cache:clear');

            event(new RoleUpdateEvent($role));

            if ($request->get('submit') == 'save') {
                return redirect()->route('admin::roles.index')->with('status', trans('notices.update_success_message'));
            } else {
                return redirect()->route('admin::roles.edit', $id)->with('status', trans('notices.update_success_message'));
            }
        }catch (\Exception $exception){
            echo $exception->getMessage();
        }

    }

    /**
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $role = $this->roleRepository->findOrFail($id);
            $role->delete();
            return response()->json([
                'msg'    => trans('notices.delete_success_message'),
                'status' => 200
            ],200);
        }
        return redirect()->route('admin::roles.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }


    /**
     * @return array
     */
    protected function getAvailablePermissions(): array
    {
        $permissions = [];
        $configuration = config(strtolower('permissions'));
        if (!empty($configuration)) {
            foreach ($configuration as $config) {
                $permissions[$config['flag']] = $config;
            }
        }

        return $permissions;
    }


    /**
     * @param int $parentId
     * @param array $allFlags
     * @return mixed
     */
    protected function getChildren($parentId, $allFlags)
    {
        $newFlagArray = [];
        foreach ($allFlags as $flagDetails) {
            if (\Arr::get($flagDetails, 'parent_flag', 'root') == $parentId) {
                $newFlagArray[] = $flagDetails['flag'];
            }
        }
        return $newFlagArray;
    }

    /**
     * @param array $permissions
     * @return array
     */
    protected function getPermissionTree($permissions): array
    {
        $sortedFlag = $permissions;
        sort($sortedFlag);
        $children['root'] = $this->getChildren('root', $sortedFlag);

        foreach (array_keys($permissions) as $key) {
            $childrenReturned = $this->getChildren($key, $permissions);
            if (count($childrenReturned) > 0) {
                $children[$key] = $childrenReturned;
            }
        }

        return $children;
    }


    /**
     * @return array
     */
    public function getJson()
    {
        $pl = [];
        foreach ($this->roleRepository->all() as $role) {
            $pl[] = [
                'value' => $role->id,
                'text' => $role->name,
            ];
        }
        return $pl;
    }

    /**
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postAssignMember(Request $request, BaseHttpResponse $response)
    {
        try {

            $user = $this->userRepository->findOrFail($request->input('pk'));
            $role = $this->roleRepository->findOrFail($request->input('value'));
            $user->roles()->sync([$role->id]);

            event(new RoleAssignmentEvent($role, $user));
            return $response;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
