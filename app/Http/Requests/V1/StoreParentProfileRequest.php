<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreParentProfileRequest extends FormRequest
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
        $id = $this->route('id');
        return [
            'first_name' => ['required','string','min:3'],
            'last_name' => ['required','string','min:3'],
            'email' => ['required','email',Rule::unique('parent_profiles')->ignore($id)],
            'image'=> ['max:255','string'],
            'phone'=>['string','max:20'],
            'dob'=>['date'],
            'address'=>['string','max:255','nullable'],
            'country_id'=>['integer','nullable'],
            'state_id'=>['integer','nullable'],
            'city_id'=>['integer','nullable'],
            'centrelink'=>['string','max:255','nullable'],
            'parent_profile_id'=>['integer'],
            'user_id'=>['integer'],
            'postcode'=>['string','max:5']
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
