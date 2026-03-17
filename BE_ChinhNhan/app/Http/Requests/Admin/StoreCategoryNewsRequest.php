<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //cate
            'cat_code' => 'nullable|string|max:150|unique:news_category,cat_code',
            'parentid' => 'nullable|integer',
            'picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_default' => 'nullable|boolean',
            'show_home' => 'nullable|boolean',
            'focus_order' => 'nullable|integer',
            'menu_order' => 'nullable|integer',
            'display' => 'nullable|boolean',
            // desc
            'cat_name' => 'required|string|max:250',
            'description' => 'nullable|string',
            'friendly_title' => 'nullable|string|max:250',
            'metakey' => 'nullable|string|max:250',
            'metadesc' => 'nullable|string',
            'lang' => 'nullable|string|max:10'
        ];
    }

    public function messages(): array
    {
        return [
           
            'cat_code.max' => 'Mã category không được vượt quá 150 ký tự',
            'cat_code.unique' => 'Mã category đã tồn tại',
            'parentid.integer' => 'Danh mục cha không hợp lệ',
            'picture.image' => 'File tải lên phải là hình ảnh',
            'picture.mimes' => 'Ảnh chỉ chấp nhận định dạng jpg, jpeg, png, webp',
            'picture.max' => 'Dung lượng ảnh tối đa là 2MB',
            'focus_order.integer' => 'Thứ tự nổi bật phải là số',
            'menu_order.integer' => 'Thứ tự menu phải là số',
            'cat_name.required' => 'Tên category không được để trống',
            'cat_name.max' => 'Tên category không được vượt quá 250 ký tự',
            'friendly_title.max' => 'SEO title không được vượt quá 250 ký tự',
            'metakey.max' => 'Meta keyword không được vượt quá 250 ký tự',
            'lang.max' => 'Ngôn ngữ không hợp lệ'
        ];
    }
}
