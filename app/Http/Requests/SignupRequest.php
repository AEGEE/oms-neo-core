<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SignupRequest extends Request
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
            'first_name'                    =>  'required',
            'last_name'                     =>  'required',
            'date_of_birth'                 =>  'required|date',
            'gender'                        =>  'required|integer|min:1|max:3',
            'personal_email'                 =>  'required|confirmed|unique:users,personal_email'.empty($id) ? "" : ", ".$id,
            'personal_email_confirmation'    =>  'required',
            'university'                    =>  'required',
            'studies_type'                  =>  'required|integer|exists:study_types,id',
            'study_field'                   =>  'required|integer|exists:study_fields,id',
            'link'                          =>  'required'
        ];
    }
}
