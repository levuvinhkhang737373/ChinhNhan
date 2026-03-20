<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashBoardController extends Controller
{
    use ApiResponse;
   public function memberStatistics()
{
    try {

        $stats = Member::selectRaw("
            COUNT(*) as total_members,
            SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as register_today,
            SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) 
                     AND YEAR(created_at) = YEAR(CURDATE()) 
                     THEN 1 ELSE 0 END) as register_this_month,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_members,
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as banned_members
        ")->first();

        $total = $stats->total_members;

        return $this->responseJson(
            true,
            'Thống kê tài khoản',
            200,
            [
                'total_members' => $stats->total_members,
                'register_today' => $stats->register_today,
                'register_this_month' => $stats->register_this_month,
                'active_members' => $stats->active_members,
                'banned_members' => $stats->banned_members,
                'active_percent' => $total > 0 ? round(($stats->active_members / $total) * 100, 2) : 0,
                'banned_percent' => $total > 0 ? round(($stats->banned_members / $total) * 100, 2) : 0,
            ],
            200
        );
    } catch (\Exception $e) {
        return $this->responseJson(false, $e->getMessage(), 500, "", 500);
    }
}

    public function extremeViews(Request $request)
    {
        try {
            $sort = strtoupper($request->input('sort', 'DESC'));
            $views = $sort === 'DESC'
                ? DB::table('news')->max('views')
                : DB::table('news')->min('views');
            $news = DB::table('news as n')
                ->join('news_desc as nd', 'n.news_id', '=', 'nd.news_id')
                ->join('news_category as ncate', 'n.cat_id', '=', 'ncate.cat_id')
                ->join('news_category_desc as ncatedesc', 'ncate.cat_id', '=', 'ncatedesc.cat_id')
                ->where('n.views', $views)
                ->select(
                    'n.news_id',
                    'n.cat_id',
                    'n.cat_list',
                    'n.picture',
                    'n.adminid',
                    'n.views',
                    'nd.title',
                    'nd.description',
                    'nd.short',
                    'nd.friendly_url',
                    'ncate.picture as category_picture',
                    'ncatedesc.cat_name'
                )
                ->get();
            return $this->responseJson(true, "Bài viết", 200, $news, 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, $e->getMessage(), 500, "", 500);
        }
    }
}
