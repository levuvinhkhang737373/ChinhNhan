<?php
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\News;
use App\Models\NewsDesc;
use App\Models\StatisticsPages;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostController extends Controller
{
    use ApiResponse;
    public function index()
    {
        try {
            $posts = News::where('display', 1)
                ->with('newsDesc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return $this->responseJson(true, "Danh sách bài viết", 200, [
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page'    => $posts->lastPage(),
                    'per_page'     => $posts->perPage(),
                    'total'        => $posts->total(),
                ], PostResource::collection($posts)], 200);
        } catch (\Exception $e) {
            report($e);
            return $this->responseJson(false, "Server Error: " . $e->getMessage(), 500, "", 500);
        }
    }

    public function show($friendly_url, Request $request)
    {
        try {
            $bearerToken = $request->bearerToken();
            if (! $bearerToken) {
                return $this->responseJson(false, 'Token hoặc UUID chưa được gửi', 401, "", 401);
            }
            $isUuid = Str::isUuid($bearerToken);
            $isJwt  = preg_match('/^[A-Za-z0-9-_]+\.[A-Za-z0-9-_]+\.[A-Za-z0-9-_]+$/', $bearerToken);
            if (! $isUuid && ! $isJwt) {
                return $this->responseJson(false, "Token/UUID không đúng định dạng", 401, "", 401);
            }
            $news = NewsDesc::where('friendly_url', $friendly_url)->first();
            if (! $news) {
                return $this->responseJson(false, 'Bài viết không tồn tại', 404, "", 404);
            }
            $member = Auth::guard('member')->id();

            $data = StatisticsPages::create([
                'url'      => $news->friendly_url,
                'date'     => time(),
                'count'    => 1,
                'mem_id'   => $member ? $member : 0,
                'module'   => 'news',
                'action'   => 'detail_news',
                'ip'       => request()->ip(),
                'guest_id' => $member ? null : $bearerToken,
            ]);
            $data->load('member');
            $news->news()->increment('views');

            return $this->responseJson(true, 'Ghi dấu thành công', 200, [
                'statistics' => $data,
                'news'       => $news,
            ], 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, 'Có lỗi hệ thống' . $e->getMessage(), 500, "", 500);
        }
    }

    public function getTopNewsPerCategory()
    {
        try {
            $categories = DB::table('news_category as nc')
                ->join('news_category_desc as ncd', 'nc.cat_id', '=', 'ncd.cat_id')
                ->where('nc.display', 1)
                ->select('nc.cat_id', 'ncd.cat_name', 'nc.display')
                ->get();

            foreach ($categories as $category) {
                $category->news = DB::table('news as n')
                    ->join('news_desc as nd', 'n.news_id', '=', 'nd.news_id')
                    ->where('n.cat_id', $category->cat_id)
                    ->select(
                        'nd.news_id',
                        'nd.title',
                        'nd.description',
                        'nd.short',
                        'n.views'
                    )
                    ->orderByDesc('n.views')
                    ->latest('n.news_id')
                    ->limit(5)
                    ->get();
            }
            return $this->responseJson(true, "Lấy danh sách thành công", 200, $categories, 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, $e->getMessage(), 500, "", 500);
        }
    }

    
    public function searchNews(Request $request)
    {
        try {
            $keyword = $request->query('keyword');
            if (! $keyword) {
                return $this->responseJson(false, "Vui lòng nhập từ khóa", 400, null, 400);
            }
            $results = \App\Models\News::search($keyword)
                        ->where('display', 1) 
                        ->query(fn ($query) => $query->with('newsDesc')) 
                        ->get();
            return $this->responseJson(true, "Tìm kiếm thành công", 200, $results, 200);
        } catch (\Exception $e) {
            report($e);
            return $this->responseJson(false, "Lỗi tìm kiếm: " . $e->getMessage(), 500, "", 500);
        }
    }
}
