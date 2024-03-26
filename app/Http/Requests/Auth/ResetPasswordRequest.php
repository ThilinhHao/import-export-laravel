<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RulePassword;

class ResetPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'token' => [
                'required',
            ],
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'min:8',
                'max:32',
                'same:password_confirmation',
                new RulePassword(),
            ],
            'password_confirmation' => [
                'required',
                'same:password',
            ],
        ];
    }
}
