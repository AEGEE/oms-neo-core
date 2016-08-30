<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ChangeEmailRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $isOauthDefined = false;
        return !$isOauthDefined;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'                 =>      'required|unique:users,contact_email|confirmed|email',
            'email_confirmation'    =>      'required'
        ];
    }
}
