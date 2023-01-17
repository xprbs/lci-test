<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetail;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\CreateUserDetailRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $validatedUser = Validator::make($request->only(['email', 'phone_number', 'password', 'password_confirmation']), (new CreateUserRequest)->rules());
            $validatedUser = $validatedUser->validate();
            $validatedUser['password'] = bcrypt($request->password);

            $validatedDetail = Validator::make($request->except(['email', 'phone_number', 'password', 'password_confirmation']), (new CreateUserDetailRequest)->rules());
            $validatedDetail = $validatedDetail->validate();
            $validatedDetail['image'] = $request->file('image')->store('image');

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
