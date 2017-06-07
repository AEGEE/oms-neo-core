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
        // TODO Find a better way to display these messages.
        // This at the very least provides a response with errors.
        // (which isn't given without this line.)
        $this->errors = $validator->errors();
        //dd($this->errors);
        return $this->errors;
    }
}
