<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Mail\MailSendOtpCode;
use App\Models\OtpCodes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    protected function generateOtpCode(Request $request):JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $userData = User::where('email', $request->email)->first();
        $userData->generateOtpCode();
        Mail::to($userData->email)->queue(new MailSendOtpCode($userData));
        return response()->json([
            "message" => "otp sudah digenerate",
            "code" => $userData->otpCode->otp
        ]);
    }

    protected function verifikasi(Request $request):JsonResponse
    {
        $request->validate([
            'otp' => 'required'
        ]);
        $otp_code = OtpCodes::where('otp', $request->otp)->first();
        if (!$otp_code) {
            return response()->json([
                'message' => 'Kode OTP yang anda masukkan salah.'
            ], 401);
        }
        $now = Carbon::now();
        if ($now > $otp_code->valid_until){
            return response()->json([
                'message' => 'Kode OTP telah kadaluarsa, silakan request kode ulang.'
            ], 401);
        }
        $user = User::find($otp_code->user_id);
        $user->email_verified_at = $now;
        $user->save();
        $otp_code->delete();
        return response()->json([
            'message' => 'Verifikasi Akun berhasil',
            'data' => new UserResource($user),
        ], 200);
    }

    public function register(RegisterRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
        $user->generateOtpCode();
        $token = JWTAuth::fromUser($user);
        Log::info($token);
        Mail::to($user->email)->queue(new MailSendOtpCode($user));
        Log::info("berhasil kirim email");
        return response()->json([
            'message' => 'Registrasi berhasil',
            'token' => $token,
            'user' => new UserResource($user),
        ], 201);
    }

    public function login(LoginRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $credentials = ['email' => $data['email'], 'password' => $data['password']];
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $userData = User::where('email', $data['email'])->first();
        $token = JWTAuth::fromUser($userData);
        return response()->json([
            "message" => "Login Berhasil",
            "user" => new UserResource($userData),
            "token" => $token
        ]);
    }

    public function logout() : JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Logout Berhasil']);
    }

    public function getUser() : JsonResponse
    {
        $user = auth()->user();
        $currentUser = User::find($user->id);
        return response()->json([
            "message" => "berhasil get user",
            "data" => new UserResource($currentUser)
        ]);
    }

    public function refresh()
    {
        $token = auth()->refresh();
        return response()->json([
            'message' => 'Token Refreshed',
            'token' => $token
        ]);
    }

}
