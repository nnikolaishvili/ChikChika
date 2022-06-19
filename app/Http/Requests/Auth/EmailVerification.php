<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class EmailVerification extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (!hash_equals((string) $this->route('id'),
            (string) $this->user()->getKey())) {
            return false;
        }

        if (!hash_equals((string) $this->route('hash'),
            sha1($this->user()->getEmailForVerification()))) {
            return false;
        }

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
            //
        ];
    }

    /**
     * Fulfill the email verification request.
     *
     * @return void
     */
    public function fulfill()
    {
        if (!$this->user()->hasVerifiedEmail()) {
            $this->user()->markEmailAsVerified();

            event(new Verified($this->user()));
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     * @return Validator
     */
    public function withValidator(Validator $validator): Validator
    {
        return $validator;
    }
}

