<?php

namespace App\Http\Requests\Estate;

use Illuminate\Foundation\Http\FormRequest;

class ImportEstateRequest extends FormRequest
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
            'import_file' => 'required|mimes:xls,xlsx'
        ];
    }

    public function messages()
    {
        return [
            'import_file.required' => 'Vui lòng chọn file import.'
        ];
    }
}
