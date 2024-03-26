<?php

namespace App\Http\Requests\Auth;

use App\Rules\RulePassword;
use App\Rules\RuleRole;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:30',
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'min:8',
                'max:32',
                new RulePassword(),
            ],
            'confirm_password' => [
                'required',
                'same:password',
            ],
            'role' => [
                'required',
                new RuleRole(),
            ],
            'terms' => 'accepted',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter your username.',
            'name.max' => 'The username may not be greater than :max characters.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'The email address has already been taken.',
            'password.required' => 'Please enter password.',
            'password.min' => 'The password must be at least :min characters.',
            'password.max' => 'The password may not be greater than :max characters.',
            'role.required' => 'Please select a permission.',
            'confirm_password.required' => 'Please enter confirm password.',
            'confirm_password.same' => 'The password and confirm password must match.',
        ];
    }
}
