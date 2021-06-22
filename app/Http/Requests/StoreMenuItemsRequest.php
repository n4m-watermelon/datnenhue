<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItemsRequest extends FormRequest
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
            'title' => 'required'
        ];
    }

    /**
     * Show messages for rules
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề trình đơn.',
        ];
    }
}
