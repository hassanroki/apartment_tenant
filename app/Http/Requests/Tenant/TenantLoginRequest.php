<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class TenantLoginRequest extends FormRequest
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
            'phone' => ['required', 'digits:11', 'numeric'],
            'password' => ['required', 'string', 'min:6']
        ];
    }

    // Message
    public function message() {
        return [
            'phone.required' => 'Phone নাম্বার দেওয়া আবশ্যক।',
            'phone.numeric' => 'Phone অবশ্যই সংখ্যা হতে হবে।',
            'phone.digits'  => 'Phone অবশ্যই ১১ ডিজিটের হতে হবে।',
            'password.required' => 'Password field দেওয়া আবশ্যক।',
            'password.min' => 'Password সর্বোনিম্ন ৬ অক্ষরের হতে হবে।',
        ];
    }
}
