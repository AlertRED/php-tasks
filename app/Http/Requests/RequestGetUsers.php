<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestGetUsers extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'api_token' => 'required',
            'page' => 'required',
            'email' => 'required',
            'password' => 'required'
        ];
    }
}
