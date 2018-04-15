<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class QuestionRequest extends FormRequest
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
    public function rules( )
    {

        dd(request()->get('topic'));

        $uri = request()::route()->uri;

        if ($uri === 'Question/store') {
            return [
                'title' => 'required|min:4|max:196|unique:questions',
                'body' => 'required|min:15'
            ];
        }

        $id = request('id');

        return [
            'title' => 'required|min:4|max:196|unique:questions,title,' .$id,
            'body' => 'required|min:15'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '请填写标题',
            'title.min' => '标题至少需要4个字',
            'title.max' => '标题最多196字',
            'title:unique' => '标题已经被占用',
            'body.required' => '请填写问题内容',
            'body.min' => '问题至少要写15个字'
        ];
    }
}
