<?php
namespace App\Http\Controllers\Member;

use App\Events\CommentCreated;
use App\Events\CommentDeleted;
use App\Events\CommentUpdated;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\News;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    use ApiResponse;

    public function index($newsId)
    {
        $comments = Comment::where('news_id', (int) $newsId)
            ->whereNull('root_id')
            ->with(['allReplies', 'member'])
            ->latest()
            ->get();
        return $this->responseJson(true, "Lấy danh sách thành công", 200, $comments, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'news_id' => 'required',
            'content' => 'required|string',
        ]);

        $member      = Auth::guard('member')->user();
        $parentId    = $request->input('parent_id');
        $rootId      = null;
        $replyToName = null;
        if (! empty($parentId)) {
            $parentComment = Comment::where('_id', $parentId)->first();
            if (! $parentComment) {
                return $this->responseJson(false, "Không tìm thấy comment cha", 404, "", 404);
            }

            $rootId = $parentComment->root_id
                ? (string) $parentComment->root_id
                : (string) $parentComment->_id;

            $replyToName = optional($parentComment->member)->full_name ?? "User";
        }
        $comment = Comment::create([
            'news_id'       => (int) $request->news_id,
            'member_id'     => (int) $member->id,
            'content'       => $request->content,
            'parent_id'     => $parentId,
            'root_id'       => $rootId,
            'reply_to_name' => $replyToName,
        ]);
        News::where('news_id', $request->news_id)->increment('comment_count');
        $comment->load('member');
        broadcast(new CommentCreated($comment))->toOthers();
        return $this->responseJson(true, "Gửi thành công", 201, $comment, 201);
    }
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'content' => 'required|string',
            ]);
            $comment = Comment::find($id);
            if (! $comment) {
                return $this->responseJson(false, "Không tìm thấy bình luận", 404, "", 404);
            }
            $member = Auth::guard('member')->user();
            if ($comment->member_id !== (int) $member->id) {
                return $this->responseJson(false, "Bạn không có quyền sửa!", 403, "", 403);
            }
            $comment->update([
                'content' => $request->content,
            ]);
            $comment->load('member');
            broadcast(new CommentUpdated($comment))->toOthers();
            return $this->responseJson(true, "Cập nhật thành công", 200, $comment, 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, $e->getMessage(), 500, "", 500);
        }
    }
    public function destroy($id)
{
    try {
        $comment = Comment::find($id);
        if (!$comment) {
            return $this->responseJson(false, "Không tìm thấy comment", 404, "", 404);
        }
        $newsId = $comment->news_id;
        $totalDeleted = 0;
        broadcast(new CommentDeleted($comment,$newsId))->toOthers();
        if (!$comment->root_id) {
            $deletedReplies = Comment::where('root_id', (string) $id)->delete();
            $comment->delete();
            $totalDeleted = $deletedReplies + 1;
        } else {
            $comment->delete();
            $totalDeleted = 1;
        }
        if ($totalDeleted > 0) {
            News::where('news_id', $newsId)->decrement('comment_count', $totalDeleted);
        }

        return $this->responseJson(true, "Xóa thành công", 200, [
            'total_deleted' => $totalDeleted,
            'news_id' => $newsId
        ], 200);

    } catch (\Exception $e) {
        return $this->responseJson(false, "Lỗi khi xóa: " . $e->getMessage(), 500, "", 500);
    }
}
}
