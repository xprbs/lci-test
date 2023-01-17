<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        try {
            DB::beginTransaction();
            $token = $request->query('token');
            $isValidToken = DB::table('password_resets')->where('token', $token)->first();
            if(!$isValidToken) throw new \Exception('Token tidak valid!');

            $request->validate([
                'password' => 'required|confirmed|min:8'
            ]);

            $resetPassword = User::where('email', $isValidToken->email)->update([
                'password' => bcrypt($request->password)
            ]);
            $deleteToken = DB::table('password_resets')->where('token', $token)->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'msg' => 'Password berhasil direset!'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }
}
