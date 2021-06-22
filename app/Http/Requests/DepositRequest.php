<?php

namespace App\Http\Requests;

use App\Supports\Http\Requests\Request;

class DepositRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'    => 'required|max:110',
            'phone'   => 'required|max:12',
            'subject' => 'required|max:110',
            'content' => 'required|min:160|max:1000'
        ];
        if (!empty($this->email)){
            $rules['email'] = 'email';
        }
        return $rules;
    }
    public function messages()
    {
        return[
            'name.required'    => 'Vui lòng nhập họ tên.',
            'name.max'         => 'Họ tên tối đa 110 ký tự',
            'email.email'      => 'Email định dạng không đúng',
            'phone.required'   => 'Vui lòng nhập số điện thoại',
            'subject.required' => 'Vui lòng nhập tiêu đề.',
            'content.required' => 'Vui lòng nhập nội dung ký gửi',
            'content.min'      => 'Nội dung ký gửi ít nhất 160 ký tự',
            'content.max'      => 'Nội dung ký tự quá dài',
        ];
    }
}
