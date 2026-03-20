<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

use App\Http\Requests\Member\ForgotPassWordRequest;
use App\Http\Requests\Member\RegisterRequest;
use App\Http\Requests\Member\ResetPasswordRequest;
use App\Http\Resources\Member\MemberResource;
use App\Mail\QuenMatKhau;
use App\Mail\WelcomeMail;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MemberController extends Controller
{

    public function informationlogin()
    {
        try {
            $user = Auth::guard('member')->user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'messages' => "Bạn chưa đăng nhập",
                    "errorCode" => 401,
                ], 401);
            }
            return response()->json([
                'status' => true,
                'data' => new MemberResource($user),
                'errorCode' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'messages' => 'Rất tiếc, đã có lỗi xảy ra trong quá trình xử lý. ' . $e->getMessage(),
                'errorCode' => 500,
            ], 500);
        }
    }

    public function forgotpassword(ForgotPassWordRequest $request)
    {

        try {
            $data = $request->validated();
            $member = Member::where(['email' => $data['email']])->first();
            if (!$member) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email không tồn tại trong hệ thống.',
                    'errorCode' => 404,
                ], 404);
            }
            $token = Str::random(64);
            $member->update(['password_token' => $token]);
            $resetLink = env('FRONTEND_URL') . "/reset-password?token=" . $token . "&email=" . $member->email;
            try {
                Mail::to($member->email)->send(new QuenMatKhau($member, $resetLink));
            } catch (\Exception $eMail) {
                Log::error("Lỗi gửi mail Reset Password: " . $eMail->getMessage());
            }
            return response()->json([
                'status' => true,
                'message' => "Link lấy lại mật khẩu đã được gửi thành công!",
                'errorCode' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'errorCode' => 500,
            ], 500);
        }
    }
    // public function forgotpassword(ForgotPassWordRequest $request)
    // {
    //     try {
    //         $member = Member::where(['email'=>$data['email']])->first();

    //         if (!$member) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Email không tồn tại.',
    //                 'errorCode' => 404,
    //             ], 404);
    //         }

    //         $token = random_int(100000, 999999);

    //         $member->update([
    //             'password_token' => $token,
    //         ]);

    //         $link = $token;

    //         Mail::to($member->email)->send(new QuenMatKhau($member, $link));

    //         return response()->json([
    //             'status' => true,
    //             'message' => "OTP đã được gửi qua mail của $member->full_name",
    //             'errorCode' => 200,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => $e->getMessage(),
    //             'errorCode' => 500,
    //         ], 500);
    //     }
    // }
    public function resetpassword(ResetPasswordRequest $request)
    {
        $data = $request->validated();
        try {
            $member = Member::where(['email' => $data['email']])
                ->where(['password_token' => $data['token']])->first();
            if (!$member) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mã Token không khớp ',
                    'errorCode' => 400,
                ], 400);
            }
            $member->update([
                'password' => Hash::make($data['password']),
                'password_token' => null
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Chúc mừng! Bạn đã đổi mật khẩu thành công.',
                'errorCode' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Rất tiếc, đã có lỗi xảy ra trong quá trình xử lý. ' . $e->getMessage(),
                'errorCode' => 500,
            ], 500);
        }
    }
    public function logout(Request $request)
    {
        try {
            Auth::guard('web')->logout();
            return response()->json([
                'status' => true,
                'message' => 'Đăng xuất thành công',
                'errorCode' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errorCode' => 500,
            ], 500);
        }
    }
    public function register(RegisterRequest $request)
    {

        try {
            $data = $request->validated();

            $member = Member::create([
                'username'  => $data['username'],
                'password' => Hash::make($data['password']), // Mã hóa password
                'email'     => $data['email'],
                'full_name' => $data['full_name'],
                'phone'     => $data['phone'],
                'status'    => 1,
                'm_status'  => 1,
                'date_join'  => now(),
            ]);

            $token = $member->createToken("Token")->accessToken;

            $response = response()->json([
                'status' => true,
                'errorCode' => 201,
                'message' => "Đăng ký thành công",
                'token' => $token,
                'data' => new MemberResource($member),
            ], 201);
            try {
                Mail::to($member->email)->send(new WelcomeMail($member));
            } catch (\Exception $eMail) {
                Log::error("MailTrap Error" . $eMail->getMessage());
            }
            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Rất tiếc, đã có lỗi xảy ra trong quá trình xử lý.' . $e->getMessage(),
                'errorCode' => 500,
            ], 500);
        }
    }
    //login trả cookie
    // public function login(Request $request)
    // {
    //     try {
    //         if (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password])) {
    //             $user = Auth::guard('web')->user();
    //             $token = $user->createToken("My Token")->accessToken;
    //             $cookie = cookie(
    //                 'auth_token',
    //                 $token,
    //                 60,
    //                 '/',
    //                 null,
    //                 false,
    //                 true
    //             );
    //             return response()->json([
    //                 'status' => true,
    //                 'message' => "Đăng nhập thành công",
    //                 null,
    //                 'user' => $user,
    //             ], 200)->cookie($cookie);
    //         } else {
    //             return response()->json([
    //                 'status'    => false,
    //                 'message'   => "Tài khoản hoặc mật khẩu không chính xác",
    //             ], 401);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => $e->getMessage(),
    //             'errorCode' => 500

    //         ], 500);
    //     }
    // }
    // login trả token thẳng
    public function login(Request $request)
    {

        try {
            if (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password])) {
                $user = Auth::guard('web')->user();
                return response()->json([
                    'status' => true,
                    'message' => "Đăng nhập thành công",
                    'token' => $user->createToken("My Token")->accessToken,
                    'user' => $user,
                ], 200);
            } else {
                return response()->json([
                    'status'    => false,
                    'message'   => "Tài khoản hoặc mật khẩu không chính xác",
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errorCode' => 500

            ], 500);
        }
        // try{

        //      $user = Member::where('username',$request->username)->first();
        //         if ($user && Hash::check($request->password,$user->password )) {
        //             $tokenResult = $user->createToken("API Token");
        //             return response()->json([
        //                 'status'    => true,
        //                 'errorCode' => 200,
        //                 'message'   => "Đăng nhập thành công",
        //                 'token'     => $tokenResult->accessToken,
        //             ]);
        //         } else {
        //             return response()->json([
        //                 'status'    => false,
        //                 'errorCode' => 401,
        //                 'message'   => "Đăng nhập không thành công",
        //             ]);
        //         }
        // }catch(\Exception $e)
        // {
        //     return response()->json([
        //         'status'=>false,
        //         'message'=>$e->getMessage(),
        //     ]);
        // }

    }

    public function updateProfile(Request $request)
    {
        try {
            $member = Auth::guard('member')->user();
            $data = $request->validate([
                'email' => 'nullable|email|unique:members,email,' . $member->id,
                'phone' => 'nullable|string|max:20',
                'password' => 'nullable|min:6',
                'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ]);
            if ($request->email) {
                $member->email = $request->email;
            }
            if ($request->phone) {
                $member->phone = $request->phone;
            }
            if ($request->password) {
                $member->password = Hash::make($request->password);
            }
            if ($request->hasFile('avatar')) {
                $member->avatar = updateImage($request->file('avatar'),$member->avatar,'uploads/member');
            }
            $member->save();
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errorCode' => 500
            ], 500);
        }
    }
}
