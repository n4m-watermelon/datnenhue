<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMailRequest extends FormRequest
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
            'name'      => 'required',
            'email'     => 'required|email',
            'phone'     => 'required',
            'subject'   => 'required',
            'content'   => 'required'
        ];
    }

    /**
     * Show message the validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'     => 'Vui lòng nhập họ tên của quý khách !',
            'phone.required'  => 'Vui lòng nhập địa chỉ của quý khách !',
            'email.required'    => 'Vui lòng nhập email của quý khách !',
            'email.email'       => 'Email không hợp lệ, vui lòng nhập lại !',
            'subject.required'    => 'Vui lòng nhập số điện thoại của quý khách !',
            'content.required'  => 'Vui lòng nhập nội dung cần liên hệ !'
        ];
    }
}
