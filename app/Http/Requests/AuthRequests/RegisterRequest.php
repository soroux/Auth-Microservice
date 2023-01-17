<?php

namespace App\Http\Requests\AuthRequests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            'application_id'=>'required|integer',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,NULL,id,application_id,' . $this->application_id,
            'phone_number' => 'required|ir_mobile|unique:users,phone_number,NULL,id,application_id,' . $this->application_id,
            'password' => 'required|string|confirmed|max:255',

        ];
    }
}
