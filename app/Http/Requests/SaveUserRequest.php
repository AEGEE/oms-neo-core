<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SaveUserRequest extends Request
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
            'address_id'            =>  'integer|exists:address,id',
            'contact_email'         =>  'email|unique:users,contact_email',
            'first_name'            =>  'max:255',
            'last_name'             =>  'max:255',
            'date_of_birth'         =>  'date',
            'gender'                =>  'integer',
            'phone'                 =>  'numeric',
            'seo_url'               =>  'max:255',
            'password'              =>  'min:8|confirmed',
            'password_confirmation' =>  'min:8',
        ];
    }
}
