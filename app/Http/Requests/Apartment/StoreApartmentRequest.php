<?php

namespace App\Http\Requests\Apartment;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreApartmentRequest extends FormRequest
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
            'rent' => ['nullable', 'numeric', 'min:1', 'max:99999'],
            'img' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'status' => ['required', 'boolean'],
            'descriptions' => ['required', 'string'],
        ];
    }

    // Message
    public function messages()
    {
        return [
            'name.required' => 'Name field দেওয়া আবশ্যক।',
            'name.max' => 'Name সর্বোচ্চ 100 অক্ষর হতে পারে।',
            'rent.numeric' => 'Rent অবশ্যই একটি সংখ্যা হতে হবে।',
            'rent.min'  => 'Rent সর্বোনিম্ন 1 হতে হবে।',
            'rent.max'  => 'Rent সর্বাধিক 99999 হতে পারে।',
            'img.mimes'  => 'Image অবশ্যই jpg, jpeg, png, gif বা webp ফাইল হতে হবে।',
            'img.max'   => 'Image সর্বাধিক 2MB হতে পারে।',
            'status.required' => 'Status দেওয়া আবশ্যক।',
            'status.boolean'  => 'Status অবশ্যই true বা false (1 বা 0) হতে হবে।',

            'descriptions.required' => 'Descriptions দেওয়া আবশ্যক।',
            'descriptions.string'   => 'Descriptions অবশ্যই টেক্সট আকারে হতে হবে।',
        ];
    }

    // Handle Field Validator
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
