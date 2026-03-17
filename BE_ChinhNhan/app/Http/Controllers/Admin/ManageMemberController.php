<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageMemberController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        try {
            $query = DB::table('statistics_pages as stpage')
                ->join('members as mb', 'stpage.mem_id', '=', 'mb.id')
                ->join('news_desc as nd', 'stpage.url', '=', 'nd.friendly_url')
                ->join('news as n', 'nd.news_id', '=', 'n.news_id')
                ->select(
                    'mb.id as member_id',
                    'mb.full_name',
                    'nd.title',
                    'nd.description',
                    'nd.short',
                    'n.news_id',
                    'n.cat_id',
                    'n.cat_list',
                    'n.picture',
                    'n.views',
                    'nd.friendly_url'
                );

            if ($request->filled('full_name')) {
                $query->where('mb.full_name', 'like','%' . $request->full_name . '%');
            }
            if ($request->filled('title')) {
                $query->where('nd.title', 'like', '%' . $request->title . '%');
            }
            $query->orderBy('stpage.created_at', 'desc');
            $activities = $query->paginate(10);
            $response = [
                'current_page' => $activities->currentPage(),
                'total_pages'  => $activities->lastPage(),
                'total_items'  => $activities->total(),
            ];
            return $this->responseJson(true, $response, 200, $activities->items(), 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, "Lỗi Server: " . $e->getMessage(), 500, "", 500);
        }
    }

}
