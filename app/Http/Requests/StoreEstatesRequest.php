<?php

namespace App\Http\Requests;

use App\Models\Estate;
use Illuminate\Foundation\Http\FormRequest;

class StoreEstatesRequest extends FormRequest
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
        $estate = Estate::find($this->get('id'));
        switch ($this->method()) {
            case 'GET' :
                break;
            case 'POST':
                return [
                    'image'       => 'required',
                    'image.*'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'title'       => 'required|max:255',
                    'title_alias' => 'required|regex:/^[a-zA-Z0-9\-]+$/',
                    'district_id' => 'required',
                    'unit_id'     => 'required',
                    'price'       => 'required',
                    'summary'     => 'max:255',
                    'area'        => 'required'
                ];
                break;
            case 'PUT':
                return [
                    'title'       => 'required|max:255',
                    'title_alias' => 'required|max:255|regex:/^[a-zA-Z0-9\-]+$/',
                    'district_id' => 'required',
                    'unit_id'     => 'required',
                    'price'       => 'required',
                    'summary'     => 'max:255',
                    'area'        => 'required'
                ];
                break;
            default:
                break;
        }
    }

    public function messages()
    {
        return [
            'title.required'        => 'Vui lòng nhập tiêu đề bất động sản.',
            'title_alias.required'  => 'Vui lòng nhập tên bí danh.',
            'district_id.required'  => 'Vui lòng chọn Quận/Huyện',
            'ward_id.required'      => 'Vui lòng chọn Phường/Xã',
            'unit_id.required'      => 'Vui lòng chọn đơn vị',
            'price.required'        => 'Vui lòng nhập giá sản phẩm.',
            'summary.max'           => 'Tóm lược nội dung tối đa 255 ký tự.',
            'area.required'         => 'Vui lòng điền diện tích sử dụng'
        ];
    }
}
