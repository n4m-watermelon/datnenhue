<?php

namespace App\Http\Controllers\Admin\Auth;


use App\Http\Controllers\BaseController;
use App\Supports\Http\Responses\BaseHttpResponse;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * @var BaseHttpResponse
     */
    protected $response;

    /**
     * ForgotPasswordController constructor.
     * @param BaseHttpResponse $response
     */
    public function __construct(BaseHttpResponse $response)
    {
        $this->middleware('guest');
        $this->response = $response;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLinkRequestForm()
    {

        return view('admin.auth.forgot-password');
    }

    /**
     * @param Request $request
     * @param $response
     * @return mixed
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return $this->response->setMessage(trans($response));
    }
}
