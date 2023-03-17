<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'email_id' => 'required|max:255',
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'mob_country_code' => 'required|max:6',
            'mobile' => 'required|max:12',
            'password' => 'required|confirmed|max:40',
            'role' => 'required',
        ];
    }
}
