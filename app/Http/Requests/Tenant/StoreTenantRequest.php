<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTenantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'numeric', 'digits:11', 'unique:tenants,phone'],
            'img' => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }


    // Custom Message
    public function messages()
    {
        return [
            'name.required' => 'Name field দেওয়া আবশ্যক।',
            'name.max' => 'Name সর্বোচ্চ 100 অক্ষর হতে পারে।',
            'phone.required' => 'Phone নাম্বার দেওয়া আবশ্যক।',
            'phone.numeric' => 'Phone অবশ্যই সংখ্যা হতে হবে।',
            'phone.digits'  => 'Phone অবশ্যই ১১ ডিজিটের হতে হবে।',
            'phone.unique'  => 'এই Phone নাম্বারটি ইতিমধ্যে ব্যবহার করা হয়েছে।',
            'img.mimes' => 'Image অবশ্যই jpg, jpeg, png বা webp ফাইল হতে হবে।',
            'img.max'   => 'Image সর্বাধিক 2MB হতে পারে।',
            'password.required' => 'Password field দেওয়া আবশ্যক।',
            'password.min' => 'Password সর্বোনিম্ন ৬ অক্ষরের হতে হবে।',
            'password.confirmed' => 'Password এবং Confirm Password মিলছে না।',
        ];
    }



    // Handle Failed Validation
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation Failed!',
                'errors' => $validator->errors(),
            ])
        );
    }
}
