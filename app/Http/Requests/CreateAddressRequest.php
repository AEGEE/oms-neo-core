<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateAddressRequest extends Request
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
            'country_id'    =>  'required|integer|exists:countries,id',
            'street'        =>  'required|max:255',
            'zipcode'       =>  'required|max:255',
            'city'          =>  'required|max:255',
        ];
    }
}
