<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddBoardPositionRequest extends Request
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
            'department_id'     =>  'required|exists:departments,id',
            'start_date'        =>  'required|date',
            'end_date'          =>  'required|date'
        ];
    }
}
