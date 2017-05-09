<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

abstract class Request extends FormRequest
{
    /**
     * {@inheritdoc}
     */
    protected function formatErrors(Validator $validator)
    {
        // TODO Find a better way to display these messages.
        // This at the very least provides a response with errors.
        // (which isn't given without this line.)
        var_dump($validator->errors()->all());
        die();
    }
}
