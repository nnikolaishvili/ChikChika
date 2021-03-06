<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'alpha_dash',
                'max:100',
                Rule::unique('users', 'username')->ignore(auth()->id())
            ],
            'is_private' => 'sometimes|in:1',
            'image' => 'sometimes|image|file|mimes:jpg,png,svg|max:10240' // max 10 mb
        ];
    }
}
