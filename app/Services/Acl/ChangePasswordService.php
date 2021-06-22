<?php

namespace App\Services\ACL;

use App\Repositories\Acl\Interfaces\UserInterface;
use App\Supports\Services\ProduceServiceInterface;
use Auth;
use Exception;
use Hash;
use Illuminate\Http\Request;

class ChangePasswordService implements ProduceServiceInterface
{
    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * ResetPasswordService constructor.
     * @param UserInterface $userRepository
     */
    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return bool|\Exception
     *
     */
    public function execute(Request $request)
    {
        if (!Auth::user()->isSuperUser()) {
            if (!Hash::check($request->input('old_password'), auth()->user()->getAuthPassword())) {
                return new Exception(trans('acl.users.current_password_not_valid'));
            }
        }

        $user = $this->userRepository->findById($request->input('id', auth()->user()->getKey()));
        $this->userRepository->update(['id' => $user->id], [
            'password' => Hash::make($request->input('password')),
        ]);

        Auth::logoutOtherDevices($request->input('password'));

        return $user;
    }
}
