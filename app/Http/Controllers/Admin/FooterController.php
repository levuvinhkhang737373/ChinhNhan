<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Footer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FooterController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {
            $footers = Footer::with('footerItems')->orderBy('id', 'desc')->get();

            return $this->responseJson(true, 'Lấy danh sách thành công', 200, $footers, 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, 'Lỗi hệ thống' . $e->getMessage(), 500, "", 500);
        }
    }

    public function store(Request $request)
    {

        $imagePath = "";
        try {
            $data = $request->validate([
                'title'   => 'required|string|max:255',
                'display' => 'nullable|in:1,0',
                'image'   => 'nullable',
                'name'    => 'nullable|string|max:255',
                'link'    => 'nullable|string|max:255',
            ]);
            if ($request->hasFile('image')) {
                $imagePath = uploadImage($request->file('image'), 'uploads/footers');
            } elseif ($request->filled('image')) {
                $imagePath = uploadBase64Image($request->input('image'), 'uploads/footers');
            }
            $footer = DB::transaction(function () use ($data, $imagePath) {
                $newFooter = Footer::create([
                    'title'   => $data['title'],
                    'display' => $data['display'] ?? 1,
                    'image'   => $imagePath,
                ]);
                if (! empty($data['name'])) {
                    $newFooter->footerItems()->create([
                        'name' => $data['name'],
                        'link' => $data['link'] ?? null,
                    ]);
                }

                return $newFooter;
            });

            return $this->responseJson(true, "Thêm thành công!", 201, $footer->load('footerItems'), 201);

        } catch (\Exception $e) {

            if ($imagePath) {
                deleteImage($imagePath);
            }
            report($e);
            return $this->responseJson(false, "Server Error: " . $e->getMessage(), 500, "", 500);
        }
    }

    public function show(string $id)
    {
        try {
            $footer = Footer::with('footerItems')->find($id);
            if (! $footer) {
                return $this->responseJson(false, "Không có Footer", 404, "", 404);
            }
            return $this->responseJson(true, "Chi tiết Footer", 200, $footer, 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, $e->getMessage(), 500, "", 500);
        }
    }

    public function update(Request $request, $id)
    {
        $footer = Footer::find($id);
        if (! $footer) {
            return $this->responseJson(false, "Không tìm thấy dữ liệu!", 404, null, 404);
        }
        $imagePath    = $footer->image;
        $oldImagePath = $footer->image;

        try {
            $data = $request->validate([
                'title'   => 'required|string|max:255',
                'display' => 'nullable|in:1,0',
                'image'   => 'nullable',
                'name'    => 'nullable|string|max:255',
                'link'    => 'nullable|string|max:255',
            ]);

            if ($request->hasFile('image')) {
                $imagePath = uploadImage($request->file('image'), 'uploads/footers');
            } elseif ($request->filled('image') && preg_match('/^data:image/', $request->input('image'))) {
                $imagePath = uploadBase64Image($request->input('image'), 'uploads/footers');
            }
            $updatedFooter = DB::transaction(function () use ($footer, $data, $imagePath, $oldImagePath) {
                $footer->update([
                    'title'   => $data['title'],
                    'display' => $data['display'] ?? $footer->display,
                    'image'   => $imagePath,
                ]);
                if (! empty($data['name'])) {
                    $footer->footerItems()->updateOrCreate(
                        ['footer_id' => $footer->id],
                        [
                            'name' => $data['name'],
                            'link' => $data['link'] ?? null,
                        ]
                    );
                }
                if ($imagePath !== $oldImagePath && $oldImagePath) {
                    deleteImage($oldImagePath);
                }
                return $footer;
            });
            return $this->responseJson(true, "Cập nhật thành công!", 200, $updatedFooter->load('footerItems'), 200);
        } catch (\Exception $e) {
            if ($imagePath !== $oldImagePath) {
                deleteImage($imagePath);
            }
            report($e);
            return $this->responseJson(false, "Server Error: " . $e->getMessage(), 500, "", 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $footer       = Footer::find($id);
            $oldImagePath = $footer->image;
            DB::transaction(function () use ($footer) {
                $footer->footerItems()->delete();
                $footer->delete();
            });
            if ($oldImagePath) {
                deleteImage($oldImagePath);
            }
            return $this->responseJson(true, "Xóa thành công!", 200, "", 200);
        } catch (\Exception $e) {
            report($e);
            return $this->responseJson(false, "Server Error: " . $e->getMessage(), 500, null, 500);
        }
    }
}
