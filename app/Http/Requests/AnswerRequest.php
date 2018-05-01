<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
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
            'question_id' => 'required|integer',
            'body' => 'required|min:15'
        ];
    }

    public function messages()
    {
        return [
            'question_id.required' => '缺少question_id',
            'question_id.integer' => 'question_id的格式不对',
            'body.required' => '请填写答案',
            'body.min' => '抱歉,答案至少需要15个字段'
        ];
    }
}
