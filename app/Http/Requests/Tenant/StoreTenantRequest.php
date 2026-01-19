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
            'phone' => ['required', 'unique:tenants,phone', 'max:11', 'min:11'],
            'img' => ['nullable', 'mimes:png,jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    // Custom Message
    public function messages()
    {
        return [
            'name.required' => 'Name field দেওয়া আবশ্যক।',
            'name.max' => 'Name সর্বোচ্চ 100 অক্ষর হতে পারে।',
            'phone.numeric' => 'Phone অবশ্যই সংখ্যা হতে হবে।',
            'phone.min'  => 'phone সর্বোনিম্ন 11 ডিজিট হতে হবে।',
            'phone.max'  => 'phone সর্বাধিক 11 ডিজিট হতে হবে।',
            'img.mimes'  => 'Image অবশ্যই jpg, jpeg, png, gif বা webp ফাইল হতে হবে।',
            'img.max'   => 'Image সর্বাধিক 2MB হতে পারে।',
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
