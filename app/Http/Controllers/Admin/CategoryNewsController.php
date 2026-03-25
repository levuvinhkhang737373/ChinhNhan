<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryNewsRequest;
use App\Http\Requests\Admin\UpdateCategoryNewsRequest;
use App\Http\Resources\CategoryNewsResource;
use App\Models\NewsCategory;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CategoryNewsController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {
            $admin = Auth::guard('admin')->user();
            if (! Gate::forUser($admin)->allows('QUẢN LÝ CATEGORY NEWS.Quản lý category news.manage')) {
                return $this->responseJson(false, "Bạn không có quyền", 403, null, 403);
            }
            $categories = NewsCategory::with('newsCategoryDesc')
                ->orderBy('cat_id', 'desc')
                ->paginate(10);
            return $this->responseJson(true, "Danh sách category", 200, CategoryNewsResource::collection($categories), 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, $e->getMessage(), 500, "", 500);
        }
    }

    public function store(StoreCategoryNewsRequest $request)
    {
        $picture = "";
        try {
            $admin = Auth::guard('admin')->user();
            if (! Gate::forUser($admin)->allows('QUẢN LÝ CATEGORY NEWS.Quản lý category news.add')) {
                return $this->responseJson(false, "Bạn không có quyền", 403, null, 403);
            }
            $data = $request->validated();
            if ($request->hasFile('picture')) {
                $data['picture'] = uploadImage($request->file('picture'), 'uploads/category');
            }
            $category = DB::transaction(function () use ($data, $picture) {
                $cat = NewsCategory::create([
                    'cat_code'    => $data['cat_code'] ?? null,
                    'parentid'    => $data['parentid'] ?? 0,
                    'picture'     => $data['picture'] ?? null,
                    'is_default'  => $data['is_default'] ?? 0,
                    'show_home'   => $data['show_home'] ?? 0,
                    'focus_order' => $data['focus_order'] ?? 0,
                    'menu_order'  => $data['menu_order'] ?? 0,
                    'views'       => 0,
                    'display'     => $data['display'] ?? 1,
                    'adminid'     => auth('admin')->id(),
                ]);
                $cat->newsCategoryDesc()->create([

                    'cat_name'       => $data['cat_name'],
                    'description'    => $data['description'] ?? "",
                    'friendly_title' => $data['friendly_title'] ?? $data['cat_name'],
                    'metakey'        => $data['metakey'] ?? "",
                    'metadesc'       => $data['metadesc'] ?? "",
                ]);
                return $cat;
            });
            $category->load('newsCategoryDesc');
            return $this->responseJson(true, "Thêm category thành công!", 201, new CategoryNewsResource($category), 201);
        } catch (\Exception $e) {
            if ($picture) {
                $deletePath = str_replace('storage/', '', $picture);
                if (Storage::disk('public')->exists($deletePath)) {
                    Storage::disk('public')->delete($deletePath);
                }
            }
            report($e);
            return $this->responseJson(false, "Server Error: " . $e->getMessage(), 500, "", 500);
        }
    }

    public function show(string $id)
    {
        try {
            $admin = Auth::guard('admin')->user();
            if (! Gate::forUser($admin)->allows('QUẢN LÝ CATEGORY NEWS.Quản lý category news.manage')) {
                return $this->responseJson(false, "Bạn không có quyền", 403, null, 403);
            }
            $category = NewsCategory::with('newsCategoryDesc')->findOrFail($id);
            return $this->responseJson(true, "Chi tiết category", 200, new CategoryNewsResource($category), 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, $e->getMessage(), 500, "", 500);
        }
    }

    public function update(UpdateCategoryNewsRequest $request, string $cat_id)
    {
        try {
            $admin = Auth::guard('admin')->user();
            if (! Gate::forUser($admin)->allows('QUẢN LÝ CATEGORY NEWS.Quản lý category news.update')) {
                return $this->responseJson(false, "Bạn không có quyền", 403, null, 403);
            }
            $newsCategory = NewsCategory::findOrFail($cat_id);
            $data         = $request->validated();
            DB::transaction(function () use ($request, $data, $newsCategory) {
                $picture = $newsCategory->picture;
                if ($request->picture) {
                    if (is_string($request->picture) && str_starts_with($request->picture, 'data:image')) {
                        deleteImage($newsCategory->picture, 'public');
                        $picture = uploadBase64Image($request->picture, 'uploads/category', 'public');
                    } elseif ($request->picture instanceof \Illuminate\Http\UploadedFile) {
                        $picture = updateImage($request->picture, $newsCategory->picture, 'uploads/category', 'public');
                    }
                }
                $newsCategory->update([
                    'cat_code'    => $data['cat_code'] ?? $newsCategory->cat_code,
                    'parentid'    => $data['parentid'] ?? $newsCategory->parentid,
                    'picture'     => $picture,
                    'is_default'  => $data['is_default'] ?? $newsCategory->is_default,
                    'show_home'   => $data['show_home'] ?? $newsCategory->show_home,
                    'focus_order' => $data['focus_order'] ?? $newsCategory->focus_order,
                    'menu_order'  => $data['menu_order'] ?? $newsCategory->menu_order,
                    'display'     => $data['display'] ?? $newsCategory->display,
                ]);

                // Cập nhật hoặc tạo mới NewsCategoryDesc
                if ($newsCategory->newsCategoryDesc) {
                    $newsCategory->newsCategoryDesc->update([
                        'cat_id'         => $newsCategory->cat_id,
                        'cat_name'       => $data['cat_name'] ?? $newsCategory->newsCategoryDesc->cat_name,
                        'description'    => $data['description'] ?? $newsCategory->newsCategoryDesc->description,
                        'friendly_title' => $data['friendly_title'] ?? $newsCategory->newsCategoryDesc->friendly_title,
                        'metakey'        => $data['metakey'] ?? $newsCategory->newsCategoryDesc->metakey,
                        'metadesc'       => $data['metadesc'] ?? $newsCategory->newsCategoryDesc->metadesc,
                    ]);
                } else {
                    $newsCategory->newsCategoryDesc()->create([
                        'cat_id'         => $newsCategory->cat_id,
                        'cat_name'       => $data['cat_name'] ?? '',
                        'description'    => $data['description'] ?? '',
                        'friendly_title' => $data['friendly_title'] ?? '',
                        'metakey'        => $data['metakey'] ?? '',
                        'metadesc'       => $data['metadesc'] ?? '',
                    ]);
                }
            });

            $newsCategory = $newsCategory->fresh()->load('newsCategoryDesc');
            return $this->responseJson(true, "Cập nhật thành công!", 200, new CategoryNewsResource($newsCategory), 200);

        } catch (\Exception $e) {
            report($e);
            return $this->responseJson(false, "Server Error: " . $e->getMessage(), 500, "", 500);
        }
    }

    public function destroy(string $cat_id)
    {
        try {
            $admin = Auth::guard('admin')->user();
            if (! Gate::forUser($admin)->allows('QUẢN LÝ CATEGORY NEWS.Quản lý category news.del')) {
                return $this->responseJson(false, "Bạn không có quyền", 403, null, 403);
            }
            $newsCategory = NewsCategory::findOrFail($cat_id);
            $picture      = $newsCategory->picture;
            DB::transaction(function () use ($newsCategory) {
                $newsCategory->newsCategoryDesc()->delete();
                $newsCategory->delete();
            });
            deleteImage($picture);
            return $this->responseJson(true, "Xóa category thành công!", 200, "", 200);
        } catch (\Exception $e) {
            report($e);
            return $this->responseJson(false, "Server Error" . $e->getMessage(), 500, "", 500);
        }
    }
}
