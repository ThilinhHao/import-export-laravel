<?php
namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RulePassword;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:30',
            'email' => 'required|email',
            'password' => [
                'required',
                'min:8',
                'max:32',
                new RulePassword(),
            ],
            'role' => 'required',
            'image' => 'image|mimes:png,jpeg|max:1024',
        ];
    }
}
