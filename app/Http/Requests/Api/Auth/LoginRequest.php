<?php

namespace App\Http\Requests\Api\Auth;

use App\Traits\HasApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class LoginRequest extends FormRequest
{
    use HasApiResponse;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'exists:users,username'],
            'password' => ['required', 'string'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return $this->validationResponse($validator->errors());
    }
}
