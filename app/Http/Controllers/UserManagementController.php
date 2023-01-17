<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    public function index()
    {
        $data = User::with('userDetail')->where('id', Auth::user()->id)->first();
        return new UserResource(true, 'Data user', $data);
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                'email' => 'sometimes|email',
                'password' => 'sometimes',
                'phone_number' => 'sometimes',
                'first_name' => 'sometimes',
                'last_name' => 'sometimes',
                'address' => 'sometimes',
                'gender' => 'sometimes',
            ]);

            if ($validator->fails()) throw new \Exception($validator->errors());
            
            $user = $this->updateUser(Auth::user()->id, $request->only('email', 'phone_number', 'password'));
            $detailUser = $this->updateDetailUser(Auth::user()->id, $request->except('email', 'phone_number', 'password'));
            $response = [$user, $detailUser];
            DB::commit();
            return new UserResource(true, 'User berhasil diupdate', $response);
        } catch (\Exception $e) {
            return new UserResource(false, 'User gagal diupdate', $e->getMessage());
        }
    }

    public function updateUser($userId, $data)
    {
        $user = User::find($userId);
        $user->email = $data['email'];
        $user->phone_number = $data['phone_number'];
        if (isset($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        $user->save();
        return $user;
    }
    
    public function updateDetailUser($userId, $data)
    {
        $user = UserDetail::where('user_id', Auth::user()->id)->first();
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->address = $data['address'];
        $user->gender = $data['gender'];

        if (array_key_exists('image', $data)) {
            $newImage = $data['image'];
            $newImage->storeAs('public/image', $newImage->hashName());
            Storage::delete('public/image/'.$user->image);
            $user->image = $newImage->hashName();
        }

        $user->save();
        return $user;
    }

    public function destroy()
    {
        try {
            $user = User::find(Auth::user()->id);
            Storage::delete('public/image/'.$user->image);
            $user->delete();
            return new UserResource(true, 'User berhasil dihapus!', null);
        } catch (\Exception $e) {
            return new UserResource(true, 'User gagal dihapus!', $e->getMessage());
        }
    }
}
