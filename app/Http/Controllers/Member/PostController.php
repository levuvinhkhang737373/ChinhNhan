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
use Illuminate\Support\Facades\Cache;
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

    public function searchNews(Request $request)
    {
        try {
            $keyword = $request->query('keyword');
            if (! $keyword) {
                return $this->responseJson(false, "Vui lòng nhập từ khóa", 400, null, 400);
            }
            $results = News::search($keyword)
                ->where('display', 1)
                ->query(fn($query) => $query->with('newsDesc'))
                ->get();
            return $this->responseJson(true, "Tìm kiếm thành công", 200, $results, 200);
        } catch (\Exception $e) {
            report($e);
            return $this->responseJson(false, "Lỗi tìm kiếm: " . $e->getMessage(), 500, "", 500);
        }
    }

    public function TopInteractiveNewsPerCategory(Request $request)
    {
        try {
            $sortBy   = $request->query('sort_by', 'views');
            $cacheKey = "top_news_categories_{$sortBy}";
            $result   = Cache::remember($cacheKey, now()->endOfDay(), function () {
                $commentSub = DB::table('comment')
                    ->where('display', 1)
                    ->select('post_id', DB::raw('COUNT(comment_id) as total_comments'))
                    ->groupBy('post_id');
                $scoreRaw   = "((n.views * 1) + (COALESCE(cmt.total_comments, 0) * 10)) / (GREATEST(TIMESTAMPDIFF(HOUR, n.created_at, NOW()), 0) + 2)";
                $rankedNews = DB::table('news as n')
                    ->join('news_desc as nd', 'n.news_id', '=', 'nd.news_id')
                    ->leftJoinSub($commentSub, 'cmt', function ($join) {
                        $join->on('n.news_id', '=', 'cmt.post_id');
                    })
                    ->select(
                        'n.cat_id',
                        'n.news_id',
                        'nd.title',
                        'nd.short',
                        'nd.friendly_url',
                        'n.views',
                        'n.created_at',
                        DB::raw('COALESCE(cmt.total_comments, 0) as total_comments'),
                        DB::raw("($scoreRaw) as interaction_score"),
                        DB::raw("ROW_NUMBER() OVER(PARTITION BY n.cat_id ORDER BY ($scoreRaw) DESC) as rank_num")
                    );
                $flatData = DB::table('news_category as ncate')
                    ->join('news_category_desc as ncatedesc', 'ncate.cat_id', '=', 'ncatedesc.cat_id')
                    ->joinSub($rankedNews, 'ranked', function ($join) {
                        $join->on('ncate.cat_id', '=', 'ranked.cat_id')
                            ->where('ranked.rank_num', '<=', 5);
                    })
                    ->where('ncate.display', 1)
                    ->select('ncate.cat_id', 'ncatedesc.cat_name', 'ranked.*')
                    ->orderBy('ncate.cat_id')
                    ->orderByDesc('ranked.interaction_score')
                    ->get();
                return $flatData->groupBy('cat_id')->map(function ($group) {
                    $first = $group->first();
                    return [
                        'cat_id'   => $first->cat_id,
                        'cat_name' => $first->cat_name,
                        'posts'    => $group->map(function ($post) {
                            return [
                                'news_id'        => $post->news_id,
                                'title'          => $post->title,
                                'short'          => $post->short ?? '',
                                'friendly_url'   => $post->friendly_url,
                                'views'          => $post->views,
                                'total_comments' => (int) $post->total_comments,
                                'score'          => round($post->interaction_score, 4),
                                'created_at'     => $post->created_at,
                            ];
                        })->values()->all(),
                    ];
                })->values()->toArray();
            });
            return $this->responseJson(true, "Lấy tin hot trong ngày thành công", 200, $result, 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, "Lỗi Server: " . $e->getMessage(), 500, "", 500);
        }
    }
}
