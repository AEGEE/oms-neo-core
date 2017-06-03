<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateBodyTypeRequest extends Request
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
            'id'            =>  'required|integer|exists:body_types,id',
            'name'          =>  'max:255|unique:body_types,name',
        ];
    }
}