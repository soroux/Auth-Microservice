<?php

namespace App\Http\Requests\ServiceRequests;

use Illuminate\Foundation\Http\FormRequest;

class AddTypeServiceRequest extends FormRequest
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
            'status'=>'required|boolean'


        ];
    }
}
