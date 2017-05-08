<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SaveBodyRequest extends Request
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
            'name'          =>  'required',
            'type_id'       =>  'required|integer',
            'address_id'    =>  'required|integer',
            'email'         =>  'required|email',
            'phone'         =>  'numeric', //Saved as null if not specified
            'description'   =>  'max:255', //Saved as null if not specified
        ];
    }
}
