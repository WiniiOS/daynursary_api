<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ImmunisationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            'name' => ['required','string','min:3'],
            'description' => ['string','min:5','nullable']
        ];

        
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // You can customize the response format here
        $response =  apiResponse([], $validator->errors()->first(), 402);
        info(error_processor($validator));

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
