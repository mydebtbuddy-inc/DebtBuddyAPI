<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
     * Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'name' => 'trim|escape|strip_tags',
            'surname' => 'trim|escape|strip_tags',
            'email' => 'trim|escape|strip_tags|lowercase',
            'password' => 'trim'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'bail|required|string|max:64',
            'surname' => 'bail|required|string|max:64',
            'email' => 'bail|required|string|email|max:255|unique:users,email',
            'password' => 'bail|required|string|min:6',
        ];
    }
}
