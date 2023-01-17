<?php

namespace App\Http\Requests\UserRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'application_id'=>'nullable|integer',
            'service_id'=>'nullable|integer',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'nullable|email|max:255|unique:users,email,NULL,id,application_id,' . $this->application_id,
            'phone_number' => 'nullable|ir_mobile|unique:users,phone_number,NULL,id,application_id,' . $this->application_id,
            'password' => 'nullable|string',
        ];
    }
}
