<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'            => 'required|max:60|min:2',
            'last_name'             => 'required|max:60|min:2',
            'email'                 => 'required|max:60|min:6|email|unique:users',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'username'              => 'required|min:4|max:30|unique:users',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required'   => 'Vui lòng nhập họ.',
            'last_name.required'    => 'Vui lòng nhập tên.',
            'username'              => 'Vui lòng nhập tên đăng nhập',
            'email.required'        => 'Vui lòng nhập email',
            'password.required'     => 'Vui lòng nhập mật khẩu',
        ];
    }
}
