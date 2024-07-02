<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class uploadProfileRequest extends FormRequest
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
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:5012',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // You can customize the response format here
        info($validator->errors());
        $response = response()->json([
            'status' => 'failed',
            'message' => $validator->errors()->first(),
            'data' => null,
            'errors' => error_processor($validator)
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
