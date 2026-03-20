<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Member;
use App\Models\News;
use App\Models\NewsDesc;
use App\Models\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use App\Notifications\NewPostNotification;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {

            $posts = News::with('newsDesc')
                ->orderBy('news_id', 'desc')
                ->paginate(10);
            return $this->responseJson(true, "Danh sách bài viết", 200, PostResource::collection($posts), 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, $e->getMessage(), 500, "", 500);
        }
    }

    public function store(StorePostRequest $request)
    {
        $picture = "";
        try {
            $data = $request->validated();
            if ($request->hasFile('picture')) {
                $picture = uploadImage($request->file('picture'), 'uploads/news');
            } elseif ($request->filled('picture')) {
                $picture = uploadBase64Image($request->input('picture'), 'uploads/news');
            }
            $post = DB::transaction(function () use ($data, $picture) {
                $catListString = implode(',', $data['cat_list'] ?? []);
                $news = News::create([
                    'cat_id'      => $data['cat_id'],
                    'cat_list'    => $catListString,
                    'picture'     => $picture,
                    'focus'       => $data['focus'] ?? 0,
                    'display'     => $data['display'] ?? 1,
                    'focus_order' => $data['focus_order'] ?? 0,
                    'menu_order'  => $data['menu_order'] ?? 0,
                    'views'       => 0,
                    'adminid'     => auth('admin')->id(),
                ]);
                $news->newsDesc()->create([
                    'title'          => $data['title'],
                    'short'          => $data['short'] ?? "",
                    'description'    => $data['description'],
                    'friendly_title' => $data['friendly_title'] ?? $data['title'],
                    'metakey'        => $data['metakey'] ?? "",
                    'metadesc'       => $data['metadesc'] ?? "",
                    'lang'           => $data['lang'] ?? 'vi',
                ]);
                return $news;
            });
            $post->load('newsDesc');
            $members = Member::all();
            NotificationFacade::send($members, new NewPostNotification($post));
            return $this->responseJson(true, "Thêm thành công!", 201, new PostResource($post), 201);
        } catch (\Exception $e) {
            if ($picture) {
                deleteImage($picture);
            }
            report($e);
            return $this->responseJson(false, "Server Error: " . $e->getMessage(), 500, "", 500);
        }
    }


    public function show(string $id)
    {
        $post = News::with('newsDesc')->findOrFail($id);
        return $this->responseJson(
            true,
            "Chi tiết bài viết",
            200,
            new PostResource($post),
            200
        );
    }
    public function update(UpdatePostRequest $request, News $news)
    {
        try {
            $data           = $request->validated();
            $newPicturePath = updateImage($request->file('picture'), $news->picture, 'uploads/post', );
            DB::transaction(function () use ($data, $news, $newPicturePath) {
                $catListString = isset($data['cat_list'])
                    ? implode(',', $data['cat_list'])
                    : $news->cat_list;
                $news->update([
                    'cat_id'      => $data['cat_id'] ?? $news->cat_id,
                    'cat_list'    => $catListString,
                    'picture'     => $newPicturePath,
                    'focus'       => $data['focus'] ?? $news->focus,
                    'display'     => $data['display'] ?? $news->display,
                    'focus_order' => $data['focus_order'] ?? $news->focus_order,
                    'menu_order'  => $data['menu_order'] ?? $news->menu_order,
                ]);
                $news->newsDesc()->updateOrCreate(
                    ['news_id' => $news->news_id],
                    [
                        'title'          => $data['title'] ?? optional($news->newsDesc)->title,
                        'short'          => $data['short'] ?? optional($news->newsDesc)->short,
                        'description'    => $data['description'] ?? optional($news->newsDesc)->description,
                        'friendly_title' => $data['friendly_title'] ?? ($data['title'] ?? optional($news->newsDesc)->friendly_title),
                        'metakey'        => $data['metakey'] ?? optional($news->newsDesc)->metakey,
                        'metadesc'       => $data['metadesc'] ?? optional($news->newsDesc)->metadesc,
                        'lang'           => $data['lang'] ?? optional($news->newsDesc)->lang,
                    ]
                );
            });
            $news->load('newsDesc');
            return $this->responseJson(true, "Cập nhật thành công!", 200, new PostResource($news), 200);
        } catch (\Exception $e) {
            report($e);
            return $this->responseJson(false, "Server Error: " . $e->getMessage(), 500, "", 500);
        }
    }

    public function destroy(News $news)
    {
        try {
            $picture = $news->picture;
            DB::transaction(function () use ($news) {
                $news->newsDesc()->delete();
                $news->delete();
            });
            deleteImage($picture);
            return $this->responseJson(true, "Xóa thành công!", 200, "", 200);
        } catch (\Exception $e) {
            report($e);
            return $this->responseJson(false, "Server Error" . $e->getMessage(), 500, "", 500);
        }
    }
    public function destroyMany(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:news,id',
        ]);
        try {
            $ids      = $request->input('ids');
            $pictures = News::whereIn('id', $ids)->pluck('picture');
            DB::transaction(function () use ($ids) {
                NewsDesc::whereIn('news_id', $ids)->delete();
                News::whereIn('id', $ids)->delete();
            });
            foreach ($pictures as $picture) {
                if ($picture) {
                    deleteImage($picture);
                }
            }
            return $this->responseJson(true, "Xóa thành công!", 200, "", 200);
        } catch (\Throwable $e) {
            report($e);
            return $this->responseJson(false, "Server Error", 500, "", 500);
        }
    }
}
