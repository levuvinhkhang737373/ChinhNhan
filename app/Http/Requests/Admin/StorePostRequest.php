<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            //table news

            'cat_id'      => 'required|integer|exists:news_category,cat_id',
            'cat_list'    => 'nullable|array',
            'cat_list.*' => 'integer|exists:news_category,cat_id',
            'picture'     => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'focus'       => 'nullable|integer|in:0,1',
            'display'     => 'nullable|integer|in:0,1',
            'focus_order' => 'nullable|integer|min:0|max:127',
            'menu_order'  => 'nullable|integer|min:0|max:32767',

            // table news_desc
            'news_id'        => 'nullable|integer|exists:news,news_id',
            'title'          => 'required|string|max:255',
            'short'          => 'nullable|string|max:255',
            'description'    => 'required|string',
            'metakey'        => 'nullable|string|max:255',
            'metadesc'       => 'nullable|string',
            'lang'           => 'nullable',



        ];
    }
    public function messages(): array
    {
        return [
            //messages news
            'cat_id.required'     => 'Vui lòng chọn danh mục chính cho bài viết.',
            'cat_id.integer'      => 'Danh mục chính phải là một số (ID).',
            'cat_id.exists'       => 'Danh mục chính bạn chọn không tồn tại.',
            'cat_list.array'      => 'Danh sách danh mục phụ phải là một mảng dữ liệu.',
            'cat_list.*.integer'  => 'Mỗi danh mục phụ phải là một số (ID).',
            'cat_list.*.exists'   => 'Một trong các danh mục phụ không tồn tại.',
            'picture.image'       => 'File tải lên phải là hình ảnh.',
            'picture.mimes'       => 'Ảnh phải có định dạng: jpeg, png, jpg, webp, hoặc gif.',
            'picture.max'         => 'Dung lượng ảnh không được vượt quá 2MB.',
            'focus.in'            => 'Trạng thái nổi bật chỉ được nhận giá trị 0 hoặc 1.',
            'display.in'          => 'Trạng thái hiển thị chỉ được nhận giá trị 0 hoặc 1.',
            'focus_order.integer' => 'Thứ tự nổi bật phải là một số.',
            'focus_order.max'     => 'Thứ tự nổi bật vượt quá giới hạn cho phép (Max: 127).',
            'menu_order.integer'  => 'Thứ tự hiển thị phải là một số.',
            'menu_order.max'      => 'Thứ tự hiển thị vượt quá giới hạn cho phép (Max: 32767).',
            // messages desc
            'news_id.integer'        => 'ID bài viết phải là một số nguyên.',
            'news_id.exists'         => 'Bài viết gốc không tồn tại trong hệ thống.',




            'title.required'         => 'Vui lòng nhập tiêu đề bài viết.',
            'title.string'           => 'Tiêu đề phải là một chuỗi văn bản.',
            'title.max'              => 'Tiêu đề không được dài quá 255 ký tự.',

            'short.string'           => 'Mô tả ngắn phải là một chuỗi văn bản.',
            'short.max'              => 'Mô tả ngắn không được dài quá 255 ký tự.',

            'description.required'   => 'Vui lòng nhập nội dung chi tiết cho bài viết.',
            'description.string'     => 'Nội dung bài viết không hợp lệ.',


            'friendly_url.string'    => 'Đường dẫn thân thiện (URL) phải là một chuỗi văn bản.',
            'friendly_url.max'       => 'Đường dẫn thân thiện không được dài quá 255 ký tự.',
            'friendly_url.unique'    => 'Đường dẫn (URL) này đã tồn tại, vui lòng chọn tên khác.',

            'friendly_title.string'  => 'Tiêu đề SEO phải là một chuỗi văn bản.',
            'friendly_title.max'     => 'Tiêu đề SEO không được dài quá 255 ký tự.',

            'metakey.string'         => 'Từ khóa SEO (Meta Key) phải là một chuỗi văn bản.',
            'metakey.max'            => 'Từ khóa SEO không được dài quá 255 ký tự.',

            'metadesc.string'        => 'Mô tả SEO (Meta Desc) phải là một chuỗi văn bản.',



        ];
    }
}
