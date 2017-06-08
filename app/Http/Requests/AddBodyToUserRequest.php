<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddBodyToUserRequest extends Request
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
            'body_id'           =>  'required|integer|exists:bodies,id',
            'start_date'        =>  'date',
            'end_date'          =>  'date',
        ];
    }
}
