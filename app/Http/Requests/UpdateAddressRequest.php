<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateAddressRequest extends Request
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
            'country_id'    =>  'integer|exists:countries,id',
            'street'        =>  'max:255',
            'zipcode'       =>  'max:255',
            'city'          =>  'max:255',
        ];
    }
}
