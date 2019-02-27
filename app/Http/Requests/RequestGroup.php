<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestGroup extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }


    public function rules()
    {
       return [
        'name' => 'required|unique:user_group|max:255'
        ];
    }
   
}
