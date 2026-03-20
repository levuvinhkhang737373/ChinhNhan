<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login(Request $request)
    {

        try {
            if (Auth::guard('admin_session')->attempt(['username' => $request->username, 'password' => $request->password])) {
                $user = Auth::guard('admin_session')->user();
                return response()->json([
                    'status' => true,
                    'message' => "Đăng nhập thành công",
                    'token' => $user->createToken("My Token")->accessToken,
                    'user'=>$user,
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
                'errorCode'=>500

            ],500);
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
     public function logout(Request $request)
    {
        try {
            Auth::guard('admin_session')->logout();
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
}
