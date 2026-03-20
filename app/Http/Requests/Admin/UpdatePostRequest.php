<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            // table news
            'cat_id' => 'nullable|integer|exists:news_category,cat_id',
            'cat_list' => 'nullable|array',
            'cat_list.*' => 'integer|exists:news_category,cat_id',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'focus' => 'nullable|integer|in:0,1',
            'display' => 'nullable|integer|in:0,1',
            'focus_order' => 'nullable|integer|min:0|max:127',
            'menu_order' => 'nullable|integer|min:0|max:32767',
            // table news_desc
            'title' => 'nullable|string|max:255',
            'short' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'metakey' => 'nullable|string|max:255',
            'metadesc' => 'nullable|string',
            'lang' => 'nullable|string'
        ];
    }
    public function messages(): array
    {
        return [
            'cat_id.integer' => 'Danh mục chính phải là một số (ID).',
            'cat_id.exists' => 'Danh mục chính bạn chọn không tồn tại.',
            'cat_list.array' => 'Danh sách danh mục phụ phải là một mảng dữ liệu.',
            'cat_list.*.integer' => 'Mỗi danh mục phụ phải là một số (ID).',
            'cat_list.*.exists' => 'Một trong các danh mục phụ không tồn tại.',
            'picture.image' => 'File tải lên phải là hình ảnh.',
            'picture.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, webp hoặc gif.',
            'picture.max' => 'Dung lượng ảnh không được vượt quá 2MB.',
            'focus.in' => 'Trạng thái nổi bật chỉ được nhận giá trị 0 hoặc 1.',
            'display.in' => 'Trạng thái hiển thị chỉ được nhận giá trị 0 hoặc 1.',
            'focus_order.integer' => 'Thứ tự nổi bật phải là một số.',
            'focus_order.max' => 'Thứ tự nổi bật vượt quá giới hạn cho phép (Max: 127).',
            'menu_order.integer' => 'Thứ tự hiển thị phải là một số.',
            'menu_order.max' => 'Thứ tự hiển thị vượt quá giới hạn cho phép (Max: 32767).',
            'title.string' => 'Tiêu đề phải là một chuỗi văn bản.',
            'title.max' => 'Tiêu đề không được dài quá 255 ký tự.',
            'short.string' => 'Mô tả ngắn phải là một chuỗi văn bản.',
            'short.max' => 'Mô tả ngắn không được dài quá 255 ký tự.',
            'description.string' => 'Nội dung bài viết không hợp lệ.',
            'metakey.string' => 'Từ khóa SEO phải là một chuỗi văn bản.',
            'metakey.max' => 'Từ khóa SEO không được dài quá 255 ký tự.',
            'metadesc.string' => 'Mô tả SEO phải là một chuỗi văn bản.',
        ];
    }
}
