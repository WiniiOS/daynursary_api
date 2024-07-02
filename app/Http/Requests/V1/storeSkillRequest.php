<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class storeSkillRequest extends FormRequest
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
        'name'=>['string','max:255','required','unique'],
        'skill_type_id'=>['integer','required'],
        'image'=>['string','max:255','nullable']
        ];
    }   

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // You can customize the response format here
        $response = response()->json([
            'status' => 'failed',
            'message' => $validator->errors()->first(),
            'data' => null,
            'errors' => error_processor($validator)
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
    
}
