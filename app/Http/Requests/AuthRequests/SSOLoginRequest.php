<?php

namespace App\Http\Requests\AuthRequests;

use Illuminate\Foundation\Http\FormRequest;

class SSOLoginRequest extends FormRequest
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
            'type_id'=>'required|exists:App\Models\Type,id',
            'application_id'=>'required|integer',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|ir_mobile',
            'code' => 'required|string|max:255',

        ];
    }
}
