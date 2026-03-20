<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMemberRequest;
use App\Http\Requests\Admin\UpdateMemberRequest;
use App\Http\Resources\Member\MemberResource;
use App\Models\Member;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {
            $admin = Auth::guard('admin')->user();
            if (! Gate::forUser($admin)->allows('QUẢN LÝ MEMBER.Quản lý tài khoản member.manage')) {
                return $this->responseJson(false, "Bạn không có quyền để thêm Member", 403, null, 403);
            }
            $members = Member::paginate(10);
            $data    = MemberResource::collection($members);

            return $this->responseJson(true, "Lấy danh sách thành viên thành công", 200, $data, 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, "Có lỗi xảy ra khi lấy danh sách", 500, $e->getMessage(), 500);
        }
    }

    public function store(StoreMemberRequest $request)
    {
        try {
            $admin = Auth::guard('admin')->user();
            if (! Gate::forUser($admin)->allows('QUẢN LÝ MEMBER.Quản lý tài khoản member.add')) {
                return $this->responseJson(false, "Bạn không có quyền để thêm Member", 403, null, 403);
            }
            $data = $request->validated();
            if (! empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            if ($request->hasFile('avatar')) {
                $file           = $request->file('avatar');
                $path           = $file->store('uploads/member', 'public');
                $data['avatar'] = 'storage/' . $path;
            }
            $member = Member::create($data);
            return $this->responseJson(true, "Thêm mới thành công", 201, new MemberResource($member), 201);
        } catch (\Exception $e) {
            return $this->responseJson(false, "Lỗi tạo mới: " . $e->getMessage(), 500, null, 500);
        }
    }

    public function show(Member $member)
    {
        try {
            $admin = Auth::guard('admin')->user();
            if (! Gate::forUser($admin)->allows('QUẢN LÝ MEMBER.Quản lý tài khoản member.manage')) {
                return $this->responseJson(false, "Bạn không có quyền để thêm Member", 403, null, 403);
            }
            return $this->responseJson(true, "Lấy chi tiết thành viên thành công", 200, new MemberResource($member), 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, "Không tìm thấy thông tin thành viên", 404, $e->getMessage(), 404);
        }
    }

    public function update(UpdateMemberRequest $request, Member $member)
    {

        try {
            $admin = Auth::guard('admin')->user();

            if (Gate::forUser($admin)->allows('QUẢN LÝ MEMBER.Quản lý tài khoản member.update')) {
                return $this->responseJson(false, "Bạn không có quyền để update Member", 403, null, 403);
            }
            $data = $request->validated();
            if (! empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            if ($request->hasFile('avatar')) {
                $oldPath = str_replace('storage/', '', $member->avatar);
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $path           = $request->file('avatar')->store('uploads/member', 'public');
                $data['avatar'] = 'storage/' . $path;
            }
            $member->update($data);
            return $this->responseJson(true, "Cập nhật thành công", 200, new MemberResource($member), 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, "Lỗi cập nhật: " . $e->getMessage(), 500, null, 500);
        }
    }

    public function destroy(Member $member)
    {
        try {
            $admin = Auth::guard('admin')->user();
            if (! Gate::forUser($admin)->allows('QUẢN LÝ MEMBER.Quản lý tài khoản member.del')) {
                return $this->responseJson(false, "Bạn không có quyền để xóa Member", 403, null, 403);
            }
            $filePath = str_replace('storage/', '', $member->avatar);
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $member->delete();
            return $this->responseJson(true, "Xóa thành viên và ảnh thành công", 200, null, 200);
        } catch (\Exception $e) {
            return $this->responseJson(false, "Lỗi khi xóa: " . $e->getMessage(), 500, null, 500);
        }
    }

}
