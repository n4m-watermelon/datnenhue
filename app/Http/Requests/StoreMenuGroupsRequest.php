<?php

namespace App\Http\Requests;

use App\Models\MenuGroup;
use Illuminate\Foundation\Http\FormRequest;

class StoreMenuGroupsRequest extends FormRequest
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
        $menu_group = MenuGroup::find($this->get('id'));
        switch ($this->method()) {
            case 'GET':
                # code...
                break;
            case 'POST':
                return [
                    'title'       => 'required|unique:menu_groups,title',
                    'title_alias' => 'unique:menu_groups,title_alias|regex:/^[a-zA-Z0-9\-]+$/'
                ];
                break;
            case 'PUT':
                return [
                    'title'       => 'required|unique:menu_groups,title,' . $menu_group->id,
                    'title_alias' => 'unique:menu_groups,title_alias,' . $menu_group->id . '|regex:/^[a-zA-Z0-9\-]+$/'
                ];
                break;
            default:
                # code...
                break;
        }

    }

    /**
     * Show messages the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required'     => 'Vui lòng nhập tên menu.',
            'title.unique'       => 'Tên menu này đã tồn tại, vui lòng chọn tên khác.',
            'title_alias.unique' => 'Tên bí danh này đã tồn tại, vui lòng chọn tên khác.',
            'title_alias.regex'  => 'Tên bí danh này không hợp lệ.'
        ];
    }
}
