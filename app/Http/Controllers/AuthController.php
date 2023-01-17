<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetail;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\CreateUserDetailRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;



class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'msg' => 'Email / Password salah!'
            ], 401);
        }

        $userData = [
            'full_name' => Auth::user()->userDetail->first_name . ' ' . Auth::user()->userDetail->last_name,
            'address' => Auth::user()->userDetail->address,
            'gender' => Auth::user()->userDetail->gender,
            'image' => url(Storage::url('image/'. Auth::user()->userDetail->image))
        ];


        return response()->json([
            'user_data' => $userData,
            'auth' => [
                'token' => $token,
                'type' => 'bearer'
            ]
        ], 200);
    }

    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $validatedUser = Validator::make($request->only(['email', 'phone_number', 'password', 'password_confirmation']), (new CreateUserRequest)->rules());
            $validatedUser = $validatedUser->validate();
            $validatedUser['password'] = bcrypt($request->password);

            $validatedDetail = Validator::make($request->except(['email', 'phone_number', 'password', 'password_confirmation']), (new CreateUserDetailRequest)->rules());
            $validatedDetail = $validatedDetail->validate();
            $image = $request->file('image');
            $image->storeAs('public/image/', $image->hashName());

            $validatedDetail['image'] = $image->hashName();

            $storeUser = User::create($validatedUser);
            $validatedDetail['user_id'] = $storeUser->id;
            $storeDetail = UserDetail::create($validatedDetail);

            DB::commit();
            return response()->json([
                'success' => true,
                'msg' => 'User berhasil dibuat'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ], 201);
        }
    }

}
