<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

abstract class Request extends FormRequest
{
    public $errors;
    /**
     * {@inheritdoc}
     */
    protected function formatErrors(Validator $validator)
    {
        $this->errors = $validator->errors()->getMessages();
        return $this->errors;
    }
}
