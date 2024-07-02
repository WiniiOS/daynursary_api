<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileChildRequest extends FormRequest
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
            'parent_profile_id' => 'required|exists:parent_profiles,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dob' => 'required|date',
            'gender' => 'required|in:Boy,Girl',
            'centrelink' => 'nullable|string',
            'child_allergies' => 'nullable|string',
            'special_needs' => 'nullable|string',
        ];
    }
}
