<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyRequest extends FormRequest
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
            //
            'name_id' => 'required',
            'twitch_id' => 'nullable|unique:applies|unique:posts',
            'youtube_id' => 'nullable|unique:applies|unique:posts',
            'x_id' => 'nullable|unique:applies|unique:posts',
        ];
    }

    public function messages() 
    {
        return [
            'name_id.required' => '※未入力です',
            'twitch_id.unique' => '※既に登録されています',
            'youtube_id.unique' => '※既に登録されています',
            'x_id.unique' => '※既に登録されています',
        ];
    }
}
