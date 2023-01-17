<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class RequestResetPasswordController extends Controller
{
    public function requestReset(Request $request)
    {
        try {
            $isValidEmail = User::where('email', $request->email)->first();
            if (!$isValidEmail) throw new \Exception('Email tidak valid!');
            $token = $this->createToken($request->email);
            Mail::to($request->email)->send(new ResetPasswordMail($token, $request->email));
            return response()->json([
                'success' => true,
                'msg' => 'Link reset password berhasil dikirim'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ], 201);
        }
    }


    public function createToken($email)
    {
        $checkToken = DB::table('password_resets')->where('email', $email)->first();
        if ($checkToken) {
            return $checkToken->token;
        }
        $token = Str::random(20);
        $this->storeToken($token, $email);
        return $token;
    }

    public function storeToken($token, $email)
    {
        $storeToken = DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token
        ]);
    }
}
