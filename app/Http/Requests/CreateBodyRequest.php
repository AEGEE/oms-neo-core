<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateBodyRequest extends Request
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
            'type_id'       =>  'required|integer|exists:body_types,id',
            'address_id'    =>  'required|integer|exists:addresses,id',
            'name'          =>  'required|max:255',
            'email'         =>  'required|email',
            'phone'         =>  'numeric',
            'description'   =>  'max:1024',
        ];
    }
}
