<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'full_name' => 'required|string|min:2|max:100',
            'username' => 'required|string|alpha_num|unique:members,username',
            'password' => 'required|string|min:6',
            'email'    => 'required|string|email|unique:members,email|max:255|email:rfc,dns',
            'phone' => [
                'required',
                'unique:members,phone',
                'regex:/^(0|\+84)(3|5|7|8|9)[0-9]{8}$/'
            ],

        ];
    }
    public function messages()
    {
        return [
            'username.required' => 'Vui lòng nhập tài khoản.',
            'username.unique'   => 'Tài khoản đã tồn tại.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email đã tồn tại.',
            'username.alpha_num' => 'Tài khoản chỉ được chứa chữ và số.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min'      => 'Mật khẩu phải từ :min ký tự trở lên.',
            'full_name.required' => 'Vui lòng nhập họ và tên',
            'phone.required' => 'Số điện thoại không được để trống.',
            'phone.regex' => 'Số điện thoại Việt Nam không hợp lệ.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
        ];
    }
}
