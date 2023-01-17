<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserDetailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'user_id' => 'sometimes',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required'  => 'Last name is required',
            'address.required' => 'Address is required',
            'gender.required' => 'Gender is required',
            'image.required' => 'Image is required',
            'image.image' => 'File must be an image',
            'image.mimes' => 'File must be of type jpg, png, jpeg, gif, or svg',
            'image.max' => 'File size must be less than 2MB'
        ];
    }

}
