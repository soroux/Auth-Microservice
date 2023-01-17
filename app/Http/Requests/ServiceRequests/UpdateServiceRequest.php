<?php

namespace App\Http\Requests\ServiceRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
            'user_id'=>'nullable|integer',
            'application_id'=>'nullable|integer',
            'name'=>'nullable|string',
            'description'=>'nullable|string',
        ];
    }
}
