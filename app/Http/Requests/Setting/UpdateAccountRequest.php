<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255',
            'sound_effects' => 'required|boolean',
            'animation' => 'required|boolean',
            'profile_picture_url' => 'nullable|image|max:2048|mimes:jpg,png,jpeg,gif,svg'
        ];
    }
}
