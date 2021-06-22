<?php

namespace App\Http\Requests;

use App\Supports\Http\Requests\Request;

class UpdateUserRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'username'          => 'required|max:30|min:4',
            'first_name'        => 'required|max:60|min:2',
            'last_name'         => 'required|max:60|min:2',
            'email'             => 'required|max:60|min:6|email',
            'address'           => 'max:255',
            'secondary_address' => 'max:255',
            'job_position'      => 'max:255',
            'phone'             => 'max:15',
        ];
        if ($this->get('is_change_password') == 1){
            $rules['password']              = 'required|min:6|max:60';
            $rules['password_confirmation'] = 'same:password';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min'      => 'Mật khẩu tối thiểu 6 ký tự.',
        ];
    }
}
