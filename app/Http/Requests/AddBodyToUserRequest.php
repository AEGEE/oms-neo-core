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
            'user_id'           =>  'required|exists:users,id',
            'body_id'           =>  'required|exists:bodies,id',
            'date_of_birth'     =>  'date',
            'date_of_birth'     =>  'date',
        ];
    }
}
