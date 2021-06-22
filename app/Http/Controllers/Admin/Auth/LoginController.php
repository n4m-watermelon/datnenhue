<?php

namespace App\Http\Controllers\Admin\Auth;

use AclManager;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * @var
     */
    protected $response;

    /**
     * Create a new controller instance.
     *
     * LoginController constructor.
     * @param Request $response
     */
    public function __construct(Request $response)
    {
        $this->middleware('guest', ['except' => 'logout']);

        $this->redirectTo = config('cms.general.admin_dir');
        $this->response = $response;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        /*dd(json_decode('{"analytics.general":true,"analytics.page":true,"analytics.browser":true,"analytics.referrer":true,"core.appearance":true,"menus.index":true,"menus.create":true,"menus.edit":true,"menus.destroy":true,"theme.index":true,"theme.activate":true,"theme.remove":true,"theme.options":true,"theme.custom-css":true,"widgets.index":true,"backups.index":true,"backups.create":true,"backups.restore":true,"backups.destroy":true,"block.index":true,"block.create":true,"block.edit":true,"block.destroy":true,"plugins.blog":true,"posts.index":true,"posts.create":true,"posts.edit":true,"posts.destroy":true,"categories.index":true,"categories.create":true,"categories.edit":true,"categories.destroy":true,"tags.index":true,"tags.create":true,"tags.edit":true,"tags.destroy":true,"comment.index":true,"comment.create":true,"comment.edit":true,"comment.destroy":true,"contacts.index":true,"contacts.edit":true,"contacts.destroy":true,"custom-fields.index":true,"custom-fields.create":true,"custom-fields.edit":true,"custom-fields.destroy":true,"galleries.index":true,"galleries.create":true,"galleries.edit":true,"galleries.destroy":true,"media.index":true,"files.index":true,"files.create":true,"files.edit":true,"files.trash":true,"files.destroy":true,"folders.index":true,"folders.create":true,"folders.edit":true,"folders.trash":true,"folders.destroy":true,"member.index":true,"member.create":true,"member.edit":true,"member.destroy":true,"pages.index":true,"pages.create":true,"pages.edit":true,"pages.destroy":true,"social-login.settings":true,"core.system":true,"users.index":true,"users.create":true,"users.edit":true,"users.destroy":true,"roles.index":true,"roles.create":true,"roles.edit":true,"roles.destroy":true,"plugins.index":true,"plugins.edit":true,"plugins.remove":true,"settings.options":true,"settings.email":true,"settings.media":true,"audit-log.index":true,"audit-log.destroy":true,"request-log.index":true,"request-log.destroy":true}',true));*/
        return view('admin.auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Request|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            $this->sendLockoutResponse($request);
        }
        $user = AclManager::getUserRepository()->getFirstBy(['username' => $request->input($this->username())]);
        if (!empty($user)) {
            if (!AclManager::getActivationRepository()->completed($user)) {
                return redirect()->back()->withErrors([trans('auth.login.not_active')]);
            }
        }

        if ($this->attemptLogin($request)) {
            AclManager::getUserRepository()->update(['id' => $user->id], ['last_login' => Carbon::now(config('app.timezone')
            )]);
            if (!session()->has('url.intended')) {
                session()->flash('url.intended', url()->current());
            }
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * @return string
     *
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return mixed
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect()->route('admin::access.login')->with('status', trans('auth.login.logout_success'));
    }
}
