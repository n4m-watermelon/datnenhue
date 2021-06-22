<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Partner;
class StorePartnerRequest extends FormRequest
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
        $partner = Partner::find($this->get('id'));
        switch ($this->method()) {
            case 'GET' :
                break;
            case 'POST':
                return [
                    'title' => 'required|unique:partners,title',
                    'image' => 'required|image'
                ];
                break;
            case 'PUT' :
                return [
                    'title' => 'required|unique:partners,title,'.$partner->id,
                    'image' => 'image'
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
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'title.unique'   => 'Tiêu đề đã tồn tại, vui lòng chọn tiêu đề khác',
            'image.required' => 'Vui lòng chọn ảnh đại diện.',
            'image.image'    => 'Ảnh minh họa mới không hợp lệ. Chỉ chấp nhận tập tin png, jpeg, bmp hoặc gif'
        ];
    }
}
