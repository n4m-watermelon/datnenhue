<?php

namespace App\Http\Requests;

use App\Models\Slider;
use Illuminate\Foundation\Http\FormRequest;

class StoreSlidersRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET':
                # code...
                break;
            case 'POST':
                return [
                    'image'    => 'required',
                    'ordering' => 'numeric|min:1'
                ];
                break;
            case 'PUT':
                return [
                    'image'    => 'image',
                    'ordering' => 'numeric|min:1'
                ];
                break;
            default:
                break;
        }

    }

    public function messages()
    {
        return [
            'image.required'   => 'Vui lòng chọn ảnh nền hiện thị!',
            'ordering.numeric' => 'Thứ tự slide phải là kiểu số!',
            'ordering.min'     => 'Thứ tự slide tối thiểu là :min!',
        ];
    }
}
