<?php
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AuthGoogleController extends Controller
{
    use ApiResponse;
    public function verifyGoogleToken(Request $request)
    {
        try {
            $request->validate([
                'credential' => 'required',
            ]);

            $response = Http::get("https://oauth2.googleapis.com/tokeninfo?id_token=" . $request->credential);

            if ($response->successful()) {
                $payload  = $response->json();
                $googleId = $payload['sub'];
                $email    = $payload['email'];
                $name     = $payload['name'];

                $member = Member::where('email', $email)->first();

                if (! $member) {
                    $member = Member::create([
                        'email'     => $email,
                        'full_name' => $name,
                        'google_id' => $googleId,
                        'provider'  => 'google',
                        'password'  => bcrypt(Str::random(24)),
                        'status'    => 1,
                    ]);
                } else {
                    if (! $member->google_id) {
                        $member->update([
                            'google_id' => $googleId,
                            'provider'  => 'google',
                        ]);
                    }
                }

                $token = $member->createToken('GoogleLoginToken')->accessToken;

                return $this->responseJson(true, "Đăng nhập thành công", 200, [
                    'member' => $member,
                    'token'  => $token,
                ], 200);
            }
            return $this->responseJson(false, "Token không hợp lệ", 401, "", 401);
        } catch (\Exception $e) {
            return $this->responseJson(false, "Server Error: " . $e->getMessage(), 500, "", 500);
        }
    }
}
