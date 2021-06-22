<?php

namespace App\Http\Controllers\Admin;

use App\Events\ACL\RoleAssignmentEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileUserRequest;
use App\Repositories\Acl\Interfaces\RoleInterface;
use App\Repositories\Acl\Interfaces\UserInterface;
use App\Services\ACL\ChangePasswordService;
use App\Services\ACL\CreateUserService;
use App\Supports\Http\Responses\BaseHttpResponse;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * @var UserInterface
     */
    protected $userRepository;
    /**
     * @var RoleInterface
     */
    protected $roleRepository;

    /**
     * UsersController constructor.
     * @param UserInterface $userRepository
     * @param RoleInterface $roleRepository
     */
    public function __construct(UserInterface $userRepository, RoleInterface $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = $this->userRepository->all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $user = $this->userRepository;
        $roles = $this->roleRepository->getList([
            '' => 'Sử dụng quyền'
        ], []);
        return view('admin.users.create', compact('user', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @param CreateUserService $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request, CreateUserService $service)
    {
        $user = $service->execute($request);
        $role = $this->roleRepository->findById($request->get('role_id'));
        if ($role) {
            event(new RoleAssignmentEvent($role, $user));
        }
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::users.index')->with('status', trans('notices.create_success_message'));
        } else {
            return redirect()->route('admin::users.edit', $user->id)->with('status', trans('notices.create_success_message'));
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
        $user = $this->userRepository->findById($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->findById($id);
        $roles = $this->roleRepository->getList([], []);
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $this->userRepository->findById($id);

        if ($user->email !== $request->input('email')) {
            $users = $this->userRepository->getModel()
                ->where('email', $request->input('email'))
                ->where('id', '<>', $user->id)
                ->count();
            if ($users) {
                return response()->json([
                    'msg' => 'Email đã tồn tại',
                    'status' => 400
                ], 400);
            }
        }

        if ($user->username !== $request->input('username')) {
            $users = $this->userRepository->getModel()
                ->where('username', $request->input('username'))
                ->where('id', '<>', $user->id)
                ->count();
            if ($users) {
                return response()->json([
                    'msg' => 'Tài khoản đã tồn tại',
                    'status' => 400
                ], 400);
            }
        }

        $user->fill($request->input());
        $user->completed_profile = 1;
        $this->userRepository->createOrUpdate($user);
        if ($request->get('role_id')) {
            $user->roles()->sync([$request->get('role_id')]);
        }
        $role = $this->roleRepository->findById($request->get('role_id'));
        if ($role) {
            event(new RoleAssignmentEvent($role, $user));
        }

        return redirect()->route('admin::users.index')->with('status', 'Cập nhật thông tin thành công !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if (\auth()->user()->getKey() == $id) {
            return response()->json(['msg' => 'Bạn không có quyền xóa tài khoản này!', 'status' => '400']);
        }
        try {
            $user = $this->userRepository->findById($id);
            $estates = $user->estates;
            if ($estates->count() > 0) {
                foreach ($estates as $estate) {
                    $estate->created_by = 1;
                    $estate->updated_by = 1;
                    $estate->save();
                }
            }
            $this->userRepository->delete($user);
            return response()->json([
                'msg' => 'Xóa thành công',
                'status' => 200
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
                'status' => $exception->getCode()
            ], $exception->getCode());
        }
    }

    /**
     * Show the form to editing
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile($id)
    {
        $user = $this->userRepository->findOrFail($id);
        $can_change_profile = Auth::user()->getKey() == $id || Auth::user()->isSuperUser();
        return view('admin.users.profile', compact('user', 'can_change_profile'));
    }

    /**
     * @param $id
     * @param UpdateProfileUserRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateProfile($id, UpdateProfileUserRequest $request, BaseHttpResponse $response)
    {
        try {
            $user = $this->userRepository->findById($id);
            $currentUser = Auth::user();
            if (($currentUser->hasPermission('admin::profile.edit') && $currentUser->getKey() === $user->id) ||
                $currentUser->isSuperUser()
            ) {
                if ($user->email !== $request->input('email')) {
                    $users = $this->userRepository->getModel()
                        ->where('email', $request->input('email'))
                        ->where('id', '<>', $user->id)
                        ->count();
                    if ($users) {
                        return $response
                            ->setError()
                            ->setMessage(trans('users.email_exist'))
                            ->withInput();
                    }
                }

                if ($user->username !== $request->input('username')) {
                    $users = $this->userRepository->getModel()
                        ->where('username', $request->input('username'))
                        ->where('id', '<>', $user->id)
                        ->count();
                    if ($users) {
                        return $response
                            ->setError()
                            ->setMessage(trans('users.username_exist'))
                            ->withInput();
                    }
                }
            }
            $user->fill($request->input());
            $user->completed_profile = 1;
            $this->userRepository->createOrUpdate($user);
            return redirect()->route('admin::profile.edit', $id)->with('status',
                trans('notices.update_success_message'));
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * @param $id
     * @param UpdatePasswordRequest $request
     * @param ChangePasswordService $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postChangePassword(
        $id,
        UpdatePasswordRequest $request,
        ChangePasswordService $service
    )
    {
        $request->merge(['id' => $id]);
        $result = $service->execute($request);

        if ($result instanceof \Exception) {
            return redirect()->back()->withInput()->withErrors([$result->getMessage()]);
        }

        return redirect()->back()->withInput()->with('status', trans('users.password_update_success'));
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function makeSuper($id, BaseHttpResponse $response)
    {
        try {
            $user = $this->userRepository->findOrFail($id);
            $user->updatePermission('superuser', true);
            $user->updatePermission('manage_supers', true);
            $user->super_user = 1;
            $user->manage_supers = 1;
            $this->userRepository->createOrUpdate($user);
            return $response
                ->setNextUrl(route('admin::users.index'))
                ->setMessage(trans('system.supper_granted'));
        } catch (\Exception $exception) {
            return $response
                ->setError()
                ->setNextUrl(route('admin::users.index'))
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function removeSuper($id, Request $request, BaseHttpResponse $response)
    {
        if (Auth::user()->getKey() == $id) {
            return redirect()->back()->withErrors([trans('system.cannot_revoke_yourself')]);
        }

        $user = $this->userRepository->findOrFail($id);

        $user->updatePermission('superuser', false);
        $user->updatePermission('manage_supers', false);
        $user->super_user = 0;
        $user->manage_supers = 0;
        $this->userRepository->createOrUpdate($user);

        return $response
            ->setNextUrl(route('admin::users.index'))
            ->setMessage(trans('system.supper_revoked'));
    }
}
