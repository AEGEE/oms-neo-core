<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'address_id'        => 'required|integer|exists:addresses,id',
            'first_name'        => 'required|max:255',
            'last_name'         => 'required|max:255',
            'date_of_birth'     => 'required|date',
            'personal_email'    => 'required|email|max:255|unique:users,personal_email',
            'gender'            => 'required',
            'phone'             => 'numeric',
            'description'       => 'max:1024',
            'password'          => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return response()->success($user, null, 'User created');
    }

    protected function guard()
    {
        return Auth::guard('guard-name');
    }
}
