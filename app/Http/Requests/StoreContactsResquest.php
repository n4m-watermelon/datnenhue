<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Contact;

class StoreContactsResquest extends FormRequest
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
        $contact = Contact::find($this->get('id'));
        switch ($this->method()) {
            case 'GET':
                # code...
                break;
            case 'POST':
                return [
                    'category_id' => 'required',
                    'title'       => 'required|unique:contacts,title',
                    'title_alias' => 'required|unique:contacts,title_alias|regex:/^[a-zA-Z0-9\-]+$/',
                    'email'       => 'required|email'
                ];
                break;
            case 'PUT':
                return [
                    'category_id' => 'required',
                    'title'       => 'required|unique:contacts,title,' . $contact->id,
                    'title_alias' => 'required|unique:contacts,title_alias,' . $contact->id . '|regex:/^[a-zA-Z0-9\-]+$/',
                    'email'       => 'required|email'
                ];
                break;
            default:
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
            'category_id.required' => 'Vui lòng chọn nhóm liên hệ.',
            'title.required'       => 'Vui lòng nhập tên liên hệ.',
            'title.unique'         => 'Tên liên hệ này đã tồn tại, vui lòng chọn tên khác.',
            'title_alias.required' => 'Vui lòng nhập tên bí danh.',
            'title_alias.unique'   => 'Tên bí danh này đã tồn tại, vui lòng chọn tên khác.',
            'title_alias.regex'    => 'Tên bí danh này không hợp lệ.',
            'email.required'       => 'Vui lòng nhập email.',
            'email.email'          => 'Địa chỉ email này không hợp lệ.',
        ];
    }
}
