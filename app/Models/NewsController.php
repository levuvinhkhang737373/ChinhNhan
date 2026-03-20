<?php
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsDesc;
use App\Models\Promotion;
use App\Models\PromotionDesc;
use App\Services\DetailSlugService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    //Service cho slug
    protected $DetailSlugService;

    public function __construct(DetailSlugService $DetailSlugService)
    {
        $this->DetailSlugService = $DetailSlugService;
    }

    public function showNewsbyViews()
    {
        try {
            //news_category_desc
            $listData = DB::table('news')
                ->where('display', 1)
                ->join('news_desc', 'news_desc.news_id', '=', 'news.news_id')
                ->join('news_category_desc', 'news_category_desc.cat_id', '=', 'news.cat_id')
                ->select(
                    'news.*',
                    'news_desc.title',
                    'news_category_desc.cat_name',
                    'news_category_desc.friendly_url as category_url',
                    'news_desc.short',
                    'news_desc.friendly_url',
                    'news_desc.metakey',
                    'news_desc.metadesc'
                )
                ->orderBy('news.views', 'desc')
                ->limit(5)->get();
            return response()->json([
                'status'   => true,
                'listNews' => $listData,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function take5news(Request $request)
    {
        try {
            $news = \DB::table('news')
                ->whereRaw("FIND_IN_SET(?, cat_list)", [12])
                ->orderBy('date_update', 'desc')
                ->take(5)
                ->get();

            if ($news->isEmpty()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'No news found for the given category.',
                ], 404);
            }

            $newsIds = $news->pluck('news_id');

            $newsDescriptions = \DB::table('news_desc')
                ->whereIn('news_id', $newsIds)
                ->get();

            $categoryDescriptions = \DB::table('news_category_desc')
                ->where('cat_id', 12)
                ->first();

            $result = $news->map(function ($item) use ($newsDescriptions, $categoryDescriptions) {
                $desc                   = $newsDescriptions->firstWhere('news_id', $item->news_id);
                $item->title            = $desc->title ?? null;
                $item->short            = $desc->short ?? null;
                $item->friendly_url     = $desc->friendly_url ?? null;
                $item->cat_name         = $categoryDescriptions->cat_name ?? null;
                $item->cat_friendly_url = $categoryDescriptions->friendly_url ?? null;

                return $item;
            });

            return response()->json([
                'status' => true,
                'data'   => $result,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred while fetching news.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function CategoryNewProdut()
    {
        $listNew = DB::table('news')
            ->where('news.cat_id', 13)
            ->where('news.display', 1)
            ->join('news_desc', 'news_desc.news_id', '=', 'news.news_id')
            ->join('news_category_desc', 'news_category_desc.cat_id', '=', 'news.cat_id')
            ->select('news.*', 'news_desc.title', 'news_category_desc.cat_name', 'news_category_desc.friendly_url as url_cat', 'news_desc.short', 'news_desc.friendly_url', 'news_desc.metakey', 'news_desc.metadesc')
            ->orderBy('news.news_id', 'desc')
            ->limit(5)
            ->get();
        return response()->json([
            'status' => true,
            'data'   => $listNew,
        ]);
    }

    public function index($slug)
    {
        try {
            $category = NewsCategory::with('newsCategoryDesc')
                ->whereHas('newsCategoryDesc', function ($query) use ($slug) {
                    $query->where('friendly_url', $slug);
                })
                ->where('display', 1)
                ->first()->cat_id;

            $listNew = DB::table('news')
                ->where('news.cat_id', $category)
                ->where('news.display', 1)
                ->join('news_desc', 'news_desc.news_id', '=', 'news.news_id')
                ->join('news_category_desc', 'news_category_desc.cat_id', '=', 'news.cat_id')
                ->select('news.*', 'news_desc.title', 'news_category_desc.cat_name', 'news_category_desc.friendly_url as url_cat', 'news_desc.short', 'news_desc.friendly_url', 'news_desc.metakey', 'news_desc.metadesc')
                ->orderBy('news.news_id', 'desc')
                ->limit(5)
                ->get();

            $listView = DB::table('news')
                ->where('news.cat_id', $category)
                ->where('display', 1)
                ->join('news_desc', 'news_desc.news_id', '=', 'news.news_id')
                ->join('news_category_desc', 'news_category_desc.cat_id', '=', 'news.cat_id')
                ->select('news.*', 'news_desc.title', 'news_category_desc.cat_name', 'news_category_desc.friendly_url as url_cat', 'news_desc.short', 'news_desc.friendly_url', 'news_desc.metakey', 'news_desc.metadesc')
                ->orderBy('news.views', 'desc')->paginate(15);
            return response()->json([
                'status'   => true,
                'listNew'  => $listNew,
                'listView' => $listView,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function search(Request $request)
    {
        try {
            if (isset($_GET['search'])) {
                $search   = $_GET['search'];
                $listNews = NewsDesc::with('news')->where('title', 'LIKE', '%' . $search . '%')->get();
                return response()->json($listNews);
            } else {
                return response()->json([
                    'message' => 'Invalid search parameters  provided for this search term.',
                    'status'  => true,
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function showDetail($slug)
    {
        try {
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function detail(Request $request, $catUrl, $slug)
    {
        try {
            if ($catUrl == "tin-khuyen-mai") {
                $promotionDesc = PromotionDesc::where('friendly_url', $slug)->first();

                return response()->json([
                    'status' => true,
                    'data'   => $promotionDesc,

                ]);
            } else {
                $urlCat = $catUrl;
                $cat_id = NewsCategory::with('newsCategoryDesc')
                    ->whereHas('newsCategoryDesc', function ($query) use ($urlCat) {
                        $query->where('friendly_url', $urlCat);
                    })
                    ->where('display', 1)
                    ->first()
                    ->cat_id;

                $newsDesc = News::with('newsDesc')->where('cat_id', $cat_id)
                    ->whereHas('newsDesc', function ($q) use ($slug) {
                        $q->where('friendly_url', 'LIKE', '%' . $slug . '%');
                    })->first();

                return response()->json([
                    'status' => true,
                    'data'   => $newsDesc ? (function () use ($newsDesc) {
                        $data = $newsDesc->toArray();

                        $data['date_post']   = date('d/m/Y', $newsDesc->date_post);
                        $data['date_update'] = date('d/m/Y', $newsDesc->date_update);

                        unset($data['created_at'], $data['updated_at']);

                        if (isset($data['news_desc'])) {
                            unset($data['news_desc']['created_at'], $data['news_desc']['updated_at']);
                        }

                        return $data;
                    })() : null,

                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getMetaCategoryNews($slug)
    {
        try {
            $category = NewsCategory::with('newsCategoryDesc')
                ->whereHas('newsCategoryDesc', function ($query) use ($slug) {
                    $query->where('friendly_url', $slug);
                })
                ->where('display', 1)
                ->first()->cat_id;

            $listNew = DB::table('news')
                ->where('news.cat_id', $category)
                ->where('news.display', 1)
                ->join('news_desc', 'news_desc.news_id', '=', 'news.news_id')
                ->join('news_category_desc', 'news_category_desc.cat_id', '=', 'news.cat_id')
                ->select('news.*', 'news_desc.title', 'news_category_desc.cat_name', 'news_category_desc.friendly_url as url_cat', 'news_desc.short', 'news_desc.friendly_url', 'news_desc.metakey', 'news_desc.metadesc')
                ->orderBy('news.news_id', 'desc')
                ->limit(5)
                ->get();

            $listView = DB::table('news')
                ->where('news.cat_id', $category)
                ->where('display', 1)
                ->join('news_desc', 'news_desc.news_id', '=', 'news.news_id')
                ->join('news_category_desc', 'news_category_desc.cat_id', '=', 'news.cat_id')
                ->select('news.*', 'news_desc.title', 'news_category_desc.cat_name', 'news_category_desc.friendly_url as url_cat', 'news_desc.short', 'news_desc.friendly_url', 'news_desc.metakey', 'news_desc.metadesc')
                ->orderBy('news.views', 'desc')->paginate(15);
            return response()->json([
                'status'   => true,
                'listNew'  => $listNew,
                'listView' => $listView,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getMetaDetail(Request $request, $catUrl, $slug)
    {
        try {
            if ($catUrl == "tin-khuyen-mai") {
                $promotionDesc = PromotionDesc::with('promotion')->where('friendly_url', $slug)
                //->select('metakey','metadesc')
                    ->first();
                $meta = [
                    'metakey'     => $promotionDesc->metakey ?? null,
                    'metadesc'    => $promotionDesc->metadesc ?? null,
                    'title'       => $promotionDesc->title ?? null,
                    'description' => $promotionDesc->description ?? null,
                    'image'       => $promotionDesc->promotion->picture ?? null,
                ];

                return response()->json([
                    'status' => true,
                    'data'   => $meta,

                ]);
            } else {
                $urlCat = $catUrl;
                $cat_id = NewsCategory::with('newsCategoryDesc')
                    ->whereHas('newsCategoryDesc', function ($query) use ($urlCat) {
                        $query->where('friendly_url', $urlCat);
                    })
                    ->where('display', 1)
                    ->first()->cat_id;

                $newsDesc = News::with('newsDesc')->where('cat_id', $cat_id)
                    ->whereHas('newsDesc', function ($q) use ($slug) {
                        $q->where('friendly_url', 'LIKE', '%' . $slug . '%');
                    })->first();

                $meta = [
                    'metakey'     => $newsDesc->newsDesc->metakey ?? null,
                    'metadesc'    => $newsDesc->newsDesc->metadesc ?? null,
                    'title'       => $newsDesc->newsDesc->title ?? null,
                    'description' => $newsDesc->newsDesc->short ?? null,
                    'image'       => $newsDesc->picture ?? null,
                    'slug'        => $newsDesc->newsDesc->friendly_url ?? null,
                    'created_at'  => $newsDesc->date_post ? Carbon::parse($newsDesc->date_post)->toIso8601String() : null,
                    'updated_at'  => $newsDesc->date_update ? Carbon::parse($newsDesc->date_update)->toIso8601String() : null,
                    'id'          => $newsDesc->news_id ?? null,
                    'catUrl'      => $catUrl,
                ];
                return response()->json([
                    'status' => true,
                    'data'   => $meta,
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function relatedNew(Request $request)
    {
        try {
            $slug = $request->slug;
            if (empty($slug)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Slug cannot be empty.',
                ], 400);
            }
            // 1. Lấy bản ghi NewsDesc kèm relation tới News
            $newsDesc = NewsDesc::where('friendly_url', $slug)
                ->with('news') // relationship NewsDesc → News
                ->first();

            if (! $newsDesc || ! $newsDesc->news) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Không tìm thấy bài viết hoặc danh mục.',
                ]);
            }

            // 2. Lấy cat_id và news_id của bài hiện tại
            $categoryId    = $newsDesc->news->cat_id;
            $currentNewsId = $newsDesc->id;

            // 3. Truy vấn các bài liên quan
            $relatedNew = DB::table('news')
                ->where('news.cat_id', $categoryId)
                ->where('news.news_id', '!=', $currentNewsId)
                ->where('news.display', 1)
                ->join('news_desc', 'news_desc.news_id', '=', 'news.news_id')
                ->select([
                    'news.news_id',
                    'news.picture',
                    DB::raw("FROM_UNIXTIME(news.date_post,   '%d/%m/%Y') as date_post"),
                    DB::raw("FROM_UNIXTIME(news.date_update, '%d/%m/%Y') as date_update"),
                    'news_desc.title',
                    'news_desc.short',
                    'news_desc.friendly_url',
                ])
                ->orderBy('news.news_id', 'desc')
                ->limit(8)
                ->get();
            return response()->json([
                'status' => true,
                'data'   => $relatedNew,

            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function checkSlug(Request $request)
    {
        try {
            $slug = $request->slug;

            if (empty($slug)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Slug cannot be empty.',
                ], 400);
            }

            // Truy vấn bảng slug_manager
            $result = DB::table('slug_manager')
                ->where('friendly_url', $slug)
                ->first();

            if (! $result) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Slug not found.',
                ], 404);
            }

            // Mapping table -> method tương ứng
            $slugHandlers = [
                'product_descs'         => 'detailProduct',
                'product_category_desc' => 'detailCategory',
                'promotion_desc'        => 'detailPromotion',
                'news_desc'             => 'detailNew',
            ];

            $catSlug = $result->cat_slug;

            if (! array_key_exists($catSlug, $slugHandlers)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unknown slug category: ' . $catSlug,
                ], 409);
            }

            $method = $slugHandlers[$catSlug];

            if (! method_exists($this->DetailSlugService, $method)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Method not implemented: ' . $method,
                ], 500);
            }

            return $this->DetailSlugService->$method($request, $slug);

        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function checkMetaSlug(Request $request)
    {
        try {
            $slug = $request->slug;

            if (empty($slug)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Slug cannot be empty.',
                ], 400);
            }

            $result = DB::table('slug_manager')
                ->where('friendly_url', $slug)
                ->first();

            if (! $result) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Slug not found.',
                ], 404);
            }

            $slugHandlers = [
                'product_descs'         => 'getProductName',
                'product_category_desc' => 'getCategoryMetaDetail',
                'promotion_desc'        => 'getPromotionMetaDetail',
                'news_desc'             => 'getNewsMetaDetail',
            ];

            $catSlug = $result->cat_slug;

            if (! array_key_exists($catSlug, $slugHandlers)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unknown slug category: ' . $catSlug,
                ], 409);
            }

            $method = $slugHandlers[$catSlug];

            if (method_exists($this->DetailSlugService, $method)) {
                return $this->DetailSlugService->$method($request, $slug);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Method không tìm thấy ' . $method,
                ], 500);
            }

        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}
