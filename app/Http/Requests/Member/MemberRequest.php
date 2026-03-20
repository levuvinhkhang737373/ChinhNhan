<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
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
            'email'=>'required|string|exists:members,email'
        ];
    }
    public function messages()
    {
        return [
        'email.required' => 'Email không được để trống.',
        'email.string'   => 'Email phải là chuỗi ký tự.',
        'email.exists'   => 'Email không tồn tại trong hệ thống.',
        ];
    }
}
