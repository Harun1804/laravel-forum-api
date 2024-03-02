<?php

namespace App\Http\Requests\Api\Feed;

use App\Traits\HasApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StoreFeedRequest extends FormRequest
{
    use HasApiResponse;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return $this->validationResponse($validator->errors());
    }
}
