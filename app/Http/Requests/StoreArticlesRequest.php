<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;

class StoreArticlesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $article = Article::find($this->get('id'));
        switch ($this->method()) {
            case 'GET' :
                break;
            case 'POST':
                return [
                    'category_id' => 'required',
                    'title'       => 'required|max:255|unique:articles,title',
                    'title_alias' => 'required|unique:articles,title_alias|regex:/^[a-zA-Z0-9\-]+$/',
                    'summary'     => 'max:255'
                ];
                break;
            case 'PUT':
                return [
                    'category_id' => 'required',
                    'title'       => 'required|max:255|unique:articles,title,' . $article->id,
                    'title_alias' => 'required|unique:articles,title_alias,' . $article->id . '|regex:/^[a-zA-Z0-9\-]+$/',
                    'summary'     => 'max:255'
                ];
                break;
            default:
                break;
        }
    }

    /**
     * Show the validation rules that apply to the request.
     *
     * @return array
     */

    public function messages()
    {
        return [
            'category_id.required' => 'Vui lòng chọn nhóm bài viết.',
            'title.required'       => 'Vui lòng nhập tiêu đề bài viết.',
            'title.max'            => 'Tiêu đề tối đa 255 ký tự.',
            'title_alias.required' => 'Vui lòng nhập tên bí danh.',
            'title_alias.unique'   => 'Tên bí danh này đã tồn tại, vui lòng chọn tên khác.',
            'title_alias.regex'    => 'Tên bí danh này không hợp lệ.',
            'summary.max'          => 'Tóm lược nội dung tối đa 255 ký tự.'
        ];
    }
}
