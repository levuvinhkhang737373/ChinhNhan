<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use ApiResponse;
    
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (! $comment) {
            return $this->responseJson(false, "Không tìm thấy bình luận", 404, "", 404);
        }
        try {
            $request->validate([
                'content' => 'required|string',
                'status'  => 'nullable|in:0,1',
            ]);
            $comment->update(
                [
                    'content' => $request->content,
                    'status'  => $request->status ?? $comment->status,
                ]
            );
            return $this->responseJson(true, "Cập nhật thành công", 200, $comment, 200);

        } catch (\Exception $e) {
            return $this->responseJson(false, "Server Error" . $e->getMessage(), 500, "", 500);
        }

    }
}
