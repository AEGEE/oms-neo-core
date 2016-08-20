<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SaveFeeRequest extends Request
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
            'name'                  =>  'required',
            'availability'          =>  'required|min:1',
            'availability_unit'     =>  'required|min:1|max:2',
            'price'                 =>  'required',
            'currency'              =>  'required'
        ];
    }
}
