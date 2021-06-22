<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRangeAcreagesRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'title' => 'required',
            'min'   => 'required|numeric',
            'max'   => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề khoảng giá.',
            'min.required'   => 'Vui lòng nhập giá trị Min.',
            'max.required'   => 'Vui lòng nhập giá trị Max.',
        ];
    }
}
