<?php

namespace App\Http\Requests;

use App\Models\Utility;
use Illuminate\Foundation\Http\FormRequest;

class StoreUtilityRequest extends FormRequest
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
        $utility = Utility::find($this->get('id'));
        switch ($this->method()) {
            case 'GET' :
                break;
            case 'POST':
                return [
                    'title' => 'required|max:255|unique:utilities,title'
                ];
                break;
            case 'PUT':
                return [
                    'title' => 'required|max:255|unique:utilities,title,' . $utility->id
                ];
                break;
            default:
                break;
        }

    }

    public function messages()
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề tiện ích.',
            'title.max'      => 'Tiêu đề tối đa 255 ký tự.',
            'title.unique'   => 'Tiêu đề này đã tồn tại, vui lòng chọn tên khác.',
        ];
    }
}
