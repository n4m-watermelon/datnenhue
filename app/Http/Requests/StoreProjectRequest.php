<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
        $project = Project::find($this->get('id'));
        switch ($this->method()) {
            case 'GET':
                break;
            case 'POST':
                return [
                    'category_id' => 'required',
                    'title'       => 'required|unique:projects,title',
                    'title_alias' => 'required|unique:projects,title_alias|regex:/^[a-zA-Z0-9\-]+$/'
                ];
                break;
            case 'PUT':
                return [
                    'category_id' => 'required',
                    'title'       => 'required|unique:projects,title,'.$project->id,
                    'title_alias' => 'required|unique:projects,title_alias,' . $project->id . '|regex:/^[a-zA-Z0-9\-]+$/'
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
            'category_id.required' => 'Vui lòng chọn nhóm dự án.',
            'title.required'       => 'Vui lòng nhập tiêu đề.',
            'title_alias.required' => 'Vui lòng nhập tên bí danh.',
            'title_alias.unique'   => 'Tên bí danh đã tồn tại, vui lòng sử dụng tên bí danh khác',
        ];
    }
}
