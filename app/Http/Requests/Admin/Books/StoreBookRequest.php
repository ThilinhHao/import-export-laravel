<?php

namespace App\Http\Requests\Admin\Books;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'name' => 'required|max:120',
            'code' => 'required|min:20|max:50|unique:books,code',
            'imported_at' => 'required',
            'description' => 'nullable|max:255',
            'image' => 'required|image|max:1024|mimes:png,jpeg',
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'Please enter the book name.',
            'name.max' => 'The book name cannot exceed 120 characters.',
            'code.required' => 'Please enter the book code.',
            'code.min' => 'The book code must be at least 20 characters.',
            'code.max' => 'The book code cannot exceed 50 characters.',
            'code.unique' => 'The book code already exists in the database.',
            'imported_at.required' => 'Please enter the import date and time.',
            'description.max' => 'The description cannot exceed 255 characters.',
            'image.required' => 'Please select an image.',
            'image.image' => 'The selected file must be an image.',
            'image.max' => 'The image size cannot exceed 1MB.',
            'image.mimes' => 'Only PNG and JPEG images are allowed.',
        ];
    }
}
