<?php

namespace App\Http\Requests\Api\Feed;

use App\Traits\HasApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CommentRequest extends FormRequest
{
    use HasApiResponse;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return $this->validationResponse($validator->errors());
    }
}
