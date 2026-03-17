<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberRequest extends FormRequest
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
        $member = $this->route('member');
        return [
            'username' => [
                'sometimes',
                'string',
                'max:150',
                Rule::unique('members', 'username')->ignore($member)
            ],

            'user_id' => 'nullable|string|max:250',

            'mem_code' => 'nullable|string|max:150',

            'email' => [
                'sometimes',
                'email',
                'max:200',
                Rule::unique('members', 'email')->ignore($member)
            ],

            'password' => 'nullable|string|min:6|max:100',

            'address' => 'nullable|string|max:200',

            'company' => 'nullable|string|max:250',

            'full_name' => 'sometimes|string|max:250',

            'provider' => 'nullable|string|max:255',

            'provider_id' => 'nullable|string|max:255',

            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'phone' => 'nullable|regex:/^[0-9]{9,11}$/',

            'gender' => 'nullable|in:male,female,other',

            'dateOfBirth' => 'nullable|date',

            'Tencongty' => 'nullable|string|max:250',

            'Masothue' => 'nullable|string|max:250',

            'Diachicongty' => 'nullable|string|max:250',

            'Sdtcongty' => 'nullable|string|max:250',

            'emailcty' => 'nullable|email|max:200',

            'MaKH' => 'nullable|string|max:200',

            'district' => 'nullable|string|max:200',

            'ward' => 'nullable|string|max:200',

            'city_province' => 'nullable|string|max:200',

            'status' => 'nullable|integer',

            'm_status' => 'nullable|integer',

            'accumulatedPoints' => 'nullable|integer|min:0',

            'date_join' => 'nullable|date',

        ];
    }
    public function messages(): array
    {
        return [

            'username.unique' => 'Username đã tồn tại',

            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',

            'password.min' => 'Mật khẩu phải ít nhất 6 ký tự',

            'phone.regex' => 'Số điện thoại không hợp lệ',

            'avatar.image' => 'Avatar phải là hình ảnh',
            'avatar.mimes' => 'Avatar phải là jpg, jpeg, png hoặc webp',
            'avatar.max' => 'Avatar tối đa 2MB',
            'dateOfBirth.date' => 'Ngày sinh không hợp lệ',
            'accumulatedPoints.integer' => 'Điểm phải là số',

        ];
    }
}
