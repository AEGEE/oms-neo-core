<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateUserRequest extends Request
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
            'address_id'        => 'required|integer|exists:addresses,id',
            'first_name'        => 'required|max:255',
            'last_name'         => 'required|max:255',
            'date_of_birth'     => 'required|date',
            'contact_email'     => 'required|email|unique:users,contact_email',
            'gender'            => 'required',
            'phone'             => 'numeric',
            'description'       => 'max:1024',
            'password'          => 'required',
        ];
    }
}
