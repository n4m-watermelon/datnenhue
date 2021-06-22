<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\SliderGroup;

class StoreSliderGroupRequest extends FormRequest
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
        $slider_group = SliderGroup::find($this->get('id'));
        switch ($this->method()) {
            case 'GET':
                # code...
                break;
            case 'POST':
                return [
                    'title' => 'required|unique:slider_groups,title'
                ];
                break;
            case 'PUT':
                return [
                    'title' => 'required|unique:slider_groups,title,' . $slider_group->id
                ];
                break;
            default:
                # code...
                break;
        }

    }

    // Show messages validate
    public function messages()
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'title.unique'   => 'Đã tồn tại tiêu đề nhóm.'
        ];
    }
}
