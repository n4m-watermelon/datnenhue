<?php

namespace App\Services\ACL;

use AclManager;
use App\Events\ACL\RoleAssignmentEvent;
use App\Repositories\Acl\Interfaces\RoleInterface;
use App\Repositories\Acl\Interfaces\UserInterface;
use App\Supports\Services\ProduceServiceInterface;
use Hash;
use Illuminate\Http\Request;

class CreateUserService implements ProduceServiceInterface
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
     * CreateUserService constructor.
     * @param UserInterface $userRepository
     * @param RoleInterface $roleRepository
     */
    public function __construct(
        UserInterface $userRepository,
        RoleInterface $roleRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param Request $request
     *
     * @return User|false|\Illuminate\Database\Eloquent\Model|mixed
     */
    public function execute(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $this->userRepository->createOrUpdate(array_merge($request->input()));

        if ($request->has('username') && $request->has('password')) {
            $this->userRepository->update(['id' => $user->id], [
                'username' => $request->input('username'),
                'password' => Hash::make($request->input('password')),
            ]);

            if (AclManager::activate($user) && $request->input('role_id')) {
                $role = $this->roleRepository->getFirstBy([
                    'id' => $request->input('role_id'),
                ]);

                if (!empty($role)) {
                    $role->users()->attach($user->id);

                    event(new RoleAssignmentEvent($role, $user));
                }
            }
        }

        return $user;
    }
}
