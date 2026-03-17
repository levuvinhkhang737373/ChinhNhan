<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
        $memberId = $this->route('member') ?? $this->route('id');
       return [
            // Tài khoản & Định danh
            'username' => [
                'required',
                'string',
                'min:3',
                'max:150',
                'alpha_dash',
                Rule::unique('members', 'username')->ignore($memberId),
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:200',
                Rule::unique('members', 'email')->ignore($memberId),
            ],
            'password' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'string',
                'min:8',
                'regex:/[a-z]/',      // Ít nhất 1 chữ thường
                'regex:/[A-Z]/',      // Ít nhất 1 chữ hoa
                'regex:/[0-9]/',      // Ít nhất 1 số
            ],
            'mem_code' => 'nullable|string|max:150',
            'user_id'  => 'nullable|string|max:250',

            // Thông tin cá nhân
            'full_name'   => 'nullable|string|max:250',
            'phone'       => 'nullable|string|max:20|regex:/^([0-9\s\-\+\(\)]*)$/',
            'gender'      => 'nullable|string|max:250',
            'dateOfBirth' => 'nullable|date_format:Y-m-d',
             'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Địa chỉ cụ thể
            'address'       => 'nullable|string|max:200',
            'ward'          => 'nullable|string|max:200',
            'district'      => 'nullable|string|max:200',
            'city_province' => 'nullable|string|max:200',
            
            // Thông tin doanh nghiệp (dựa trên schema của bạn)
            'company'      => 'nullable|string|max:250',
            'Tencongty'    => 'nullable|string|max:250',
            'Masothue'     => 'nullable|string|max:250',
            'Diachicongty' => 'nullable|string|max:250',
            'Sdtcongty'    => 'nullable|string|max:250',
            'emailcty'     => 'nullable|email|max:250',
            'MaKH'         => 'nullable|string|max:250',
            
            // Trạng thái & Hệ thống
            'status'            => 'nullable|integer|in:0,1',
            'm_status'          => 'nullable|integer',
            'accumulatedPoints' => 'nullable|integer|min:0',
            'date_join'         => 'nullable|date_format:Y-m-d',
            'provider'          => 'nullable|string|max:255',
            'provider_id'       => 'nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'required'    => ':attribute bắt buộc phải nhập.',
            'unique'      => ':attribute này đã được sử dụng trên hệ thống.',
            'max'         => ':attribute không được dài quá :max ký tự.',
            'min'         => ':attribute phải có tối thiểu :min ký tự.',
            'email'       => 'Định dạng email không hợp lệ.',
            'integer'     => ':attribute phải là con số.',
            'date_format' => ':attribute không đúng định dạng ngày YYYY-MM-DD.',
            'password.regex' => 'Mật khẩu phải bao gồm ít nhất một chữ hoa, một chữ thường và một chữ số.',
            'phone.regex'    => 'Số điện thoại chứa ký tự không hợp lệ.',
            'in'             => 'Giá trị của :attribute không nằm trong danh sách cho phép.',
        ];
    }
    public function attributes(): array
    {
        return [
            'username'     => 'Tên đăng nhập',
            'email'        => 'Email thành viên',
            'password'     => 'Mật khẩu',
            'full_name'    => 'Họ và tên',
            'phone'        => 'Số điện thoại',
            'dateOfBirth'  => 'Ngày sinh',
            'Tencongty'    => 'Tên công ty',
            'Masothue'     => 'Mã số thuế',
            'emailcty'     => 'Email công ty',
            'status'       => 'Trạng thái tài khoản',
            'accumulatedPoints' => 'Điểm tích lũy',
        ];
    }
}
