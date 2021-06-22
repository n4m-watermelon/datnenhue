<?php

namespace App\Http\Requests;

use App\Supports\Http\Requests\Request;

class UpdateProfileUserRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'          => 'required|max:30|min:4',
            'first_name'        => 'required|max:60|min:2',
            'last_name'         => 'required|max:60|min:2',
            'email'             => 'required|max:60|min:6|email',
            'dob'               => 'date|nullable',
            'address'           => 'max:255',
            'secondary_address' => 'max:255',
            'job_position'      => 'max:255',
            'phone'             => 'max:15',
            'secondary_phone'   => 'max:15',
            'secondary_email'   => 'max:60|email|nullable',
            'gender'            => 'max:255',
            'website'           => 'max:255',
            'skype'             => 'max:255',
            'facebook'          => 'max:255',
            'twitter'           => 'max:255',
            'google_plus'       => 'max:255',
            'youtube'           => 'max:255',
            'github'            => 'max:255',
            'interest'          => 'max:255',
            'about'             => 'max:400',
        ];
    }

    public function messages()
    {
        return [
            'display_name.required'      => 'Tên hiển thị là thông tin bắt buộc.',
            'email.required'             => 'Email là thông tin bắt buộc.',
            'email.email'                => 'Thông tin email không đúng định dạng.',
            'password.required'          => 'Mật khẩu là thông tin bắt buộc.',
            'password.min'               => 'Mật khẩu tối thiểu phải chứa :min ký tự.',
            'password_confirmation.same' => 'Mật khẩu không trùng nhau.'
        ];
    }
}
