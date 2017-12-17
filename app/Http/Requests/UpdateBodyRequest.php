<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateBodyRequest extends Request
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
            'name'          =>  'max:255',
            'type_id'       =>  'integer|exists:body_types,id',
            'address_id'    =>  'integer|exists:addresses,id',
            'email'         =>  'email|unique:bodies,email',
            'phone'         =>  'numeric',
            'description'   =>  'max:1024',
        ];
    }
}
