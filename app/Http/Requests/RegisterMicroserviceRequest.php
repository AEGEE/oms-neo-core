<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

use App\Models\GlobalOption;

class RegisterMicroserviceRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $key = GlobalOption::where('code', 'shared_secret')->firstOrFail();

        $xAuthToken = isset($_SERVER['HTTP_X_API_KEY']) ? $_SERVER['HTTP_X_API_KEY'] : '';

        if(empty($xAuthToken) || $xAuthToken != $key->value) {
            return false;
        }
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
            'name'      =>  'required',
            'code'      =>  'required|unique:modules,code',
            'base_url'  =>  'required|unique:modules,base_url',
            'pages'     =>  'required'
        ];
    }
}
