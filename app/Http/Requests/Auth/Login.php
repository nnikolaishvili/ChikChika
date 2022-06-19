<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class Login extends FormRequest
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
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember' => 'sometimes'
        ];
    }

    /**
     * Get credentials: [ email, password ]
     *
     * @return array
     */
    public function getCredentials(): array
    {
        return $this->only('email', 'password');
    }
}
