<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordChangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //

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
            'old_password' => 'required|min:6',
            'password' => 'required|confirmed|min:6',
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => '原始密码不能为空',
            'old_password.min' => '原始密码不能少于6位',
            'password.required' => '新密码是必须填写的',
            'password.confirmed' => '新密码和确认密码不一致',
            'password.min' => '新密码不能少于6位',
        ];
    }
}
