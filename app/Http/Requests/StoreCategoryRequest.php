<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
        $categories = Category::find($this->get('id'));
        switch ($this->method()) {
            case 'GET':
                # code...
                break;
            case 'POST':
                return [
                    'title'       => 'required|unique:categories,title',
                    'title_alias' => 'required|unique:categories,title_alias|regex:/^[a-zA-Z0-9\-]+$/',
                ];
                break;
            case 'PUT':
                return [
                    'title'       => 'required|unique:categories,title,' . $categories->id,
                    'title_alias' => 'required|unique:categories,title_alias,' . $categories->id . '|regex:/^[a-zA-Z0-9\-]+$/',
                ];
                break;
            default:
                # code...
                break;
        }

    }

    /**
     * Show messages rules that apply to the request.
     *
     * @var mixed
     */
    public function messages()
    {
        return [
            'title.required'       => 'Vui lòng nhập tên nhóm',
            'title.unique'         => 'Tên nhóm này đã tồn tại, vui lòng chọn tên khác',
            'title_alias.required' => 'Vui lòng nhập tên bí danh',
            'title_alias.unique'   => 'Tên bí danh này đã tồn tại, vui lòng chọn tên khác',
            'title_alias.regex'    => 'Tên bí danh này không hợp lệ',
        ];
    }
}
