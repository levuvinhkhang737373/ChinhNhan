<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $data = Banner::all();
            return $this->responseJson(true, "Danh sách Banner", 200, $data, 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, "Server Error" . $e->getMessage(), 500, "", 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title'    => 'nullable|string|max:255',
                'image'    => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
                'link'     => 'required|url|max:255',
                'position' => 'required|string|max:255',
            ]);
            $imagePath = null;

            if ($request->image) {
                if (str_contains($request->image, 'base64')) {
                    $imagePath = uploadBase64Image($request->image, 'uploads/banners');
                }
            }
            if ($request->hasFile('image')) {
                $imagePath = uploadImage($request->file('image'), 'uploads/banners');
            }

            $banner = Banner::create([
                'title'      => $request->title,
                'image'      => $imagePath,
                'link'       => $request->link,
                'position'   => $request->position,
                'is_active'  => $request->is_active ?? 1,
                'sort_order' => $request->sort_order ?? 0,
            ]);

            return $this->responseJson(
                true,
                'Tạo banner thành công',
                200,
                new BannerResource($banner),
                200
            );
        } catch (\Exception $e) {
            return $this->responseJson(false, $e->getMessage(), 500, null, 500);
        }
    }

    public function show($id)
    {
        try {
            $banner = Banner::find($id);

            if (! $banner) {
                return $this->responseJson(false, 'Không tìm thấy banner', 404, null, 404);
            }

            return $this->responseJson(
                true,
                'Chi tiết banner',
                200,
                new BannerResource($banner),
                200
            );
        } catch (\Exception $e) {
            return $this->responseJson(false, $e->getMessage(), 500, null, 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {

            $banner = Banner::find($id);
            if (! $banner) {
                return $this->responseJson(false, 'Không tìm thấy banner', 404, null, 404);
            }
            $imagePath = $banner->image;

            if ($request->image) {
                if (str_contains($request->image, 'base64')) {
                    deleteImage($banner->image);
                    $imagePath = uploadBase64Image($request->image, 'uploads/banners');
                }
            }
            if ($request->hasFile('image')) {
                $imagePath = updateImage($request->file('image'), $banner->image, 'uploads/banners');
            }

            $banner->update([
                'title'      => $request->title ?? $banner->title,
                'image'      => $imagePath,
                'link'       => $request->link,
                'position'   => $request->position,
                'is_active'  => $request->is_active ?? $banner->is_active,
                'sort_order' => $request->sort_order ?? $banner->sort_order,
            ]);

            return $this->responseJson(
                true,
                'Cập nhật thành công',
                200,
                new BannerResource($banner),
                200
            );
        } catch (\Exception $e) {
            return $this->responseJson(false, $e->getMessage(), 500, null, 500);
        }
    }

    public function destroy($id)
    {
        try {
            $banner = Banner::find($id);

            if (! $banner) {
                return $this->responseJson(false, 'Không tìm thấy banner', 404, null, 404);
            }

            deleteImage($banner->image);
            $banner->delete();

            return $this->responseJson(true, 'Xoá thành công', 200, null, 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, $e->getMessage(), 500, null, 500);
        }
    }
}
