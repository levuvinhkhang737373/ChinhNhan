<?php
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryNewsResource;
use App\Models\NewsCategory;
use App\Traits\ApiResponse;

class CategoryNewsController extends Controller
{
    use ApiResponse;
    public function index()
    {
        try {
            $category = NewsCategory::where('display', 1)
                ->with('newsCategoryDesc')
                ->get();
            return $this->responseJson(true, "Danh sách category", 200, CategoryNewsResource::collection($category), 200);
        } catch (\Exception $e) {
            report($e);
            return $this->responseJson(false, "Server Error: " . $e->getMessage(), 500, "", 500);
        }
    }
    // public function listCategoryDisplayTrue()
    // {
    //     try {
    //         $categories = NewsCategory::where('display', 1)
    //             ->with(['newsCategoryDesc:cat_id,cat_name,description'])
    //             ->select('cat_id', 'picture', 'display')
    //             ->get();
    //           return $this->responseJson(true, "Danh sách category", 200,$categories, 200);
    //     } catch (\Exception $e) {
    //         report($e);
    //         return $this->responseJson(false, "Server Error: " . $e->getMessage(), 500, "", 500);
    //     }
    // }
}
