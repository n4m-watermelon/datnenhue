<?php

namespace App\Http\Requests;

use App\Models\Box;
use Illuminate\Foundation\Http\FormRequest;

class BoxStoreRequest extends FormRequest
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
        $article = Box::find($this->get('id'));
        switch ($this->method()) {
            case 'GET' :
                break;
            case 'POST':
                return [
                    'image'            => 'required',
                    'image.*'          => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'min_price'        => 'required',
                    'max_acreage'      => 'required',
                    'max_price'        => 'required',
                    'min_acreage'      => 'required',
                    'title'            => 'required|max:255',
                ];
                break;
            case 'PUT':
                return [
                    'min_price'        => 'required',
                    'max_acreage'      => 'required',
                    'max_price'        => 'required',
                    'min_acreage'      => 'required',
                    'title'            => 'required|max:255',
                ];
                break;
            default:
                break;
        }
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'image.required'            => 'Vui lòng ảnh đại diện.',
            'max_price.required'        => 'Vui lòng nhập mức giá lớn nhất.',
            'min_price.required'        => 'Vui lòng nhập mức giá nhỏ nhất.',
            'min_acreage.required'      => 'Vui lòng chọn mức diện tích nhỏ nhất.',
            'max_acreage.required'      => 'Vui lòng chọn mức diện tích lớn nhất.',
            'title.required'            => 'Vui lòng nhập tiêu đề bài viết.',
            'title.max'                 => 'Tiêu đề tối đa 255 ký tự.',
        ];
    }
}
