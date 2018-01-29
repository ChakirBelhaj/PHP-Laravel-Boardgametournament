<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterRequest;
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
  protected $redirectTo = '/home';

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
   * @param  array $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data)
  {
    return Validator::make($data, [
      'firstname' => 'required|max:190',
      'insertion' => 'nullable|max:20',
      'lastname' => 'required|max:190',
      'city' => 'nullable|max:100',
      'username' => 'required|unique:users|max:190',
      'email' => 'required|unique:users|max:190|email',
      'password' => 'confirmed|required',
      'image' => 'image'
    ]);
  }

  /**
   * Create a new user instance after a valid registration.
   *
   * @param  array $data
   * @return \App\User
   */
  protected function create(array $data)
  {
    return User::create([
      'firstname' => $data['firstname'],
      'insertion' => $data['insertion'],
      'lastname' => $data['lastname'],
      'city' => $data['city'],
      'username' => $data['username'],
      'email' => $data['email'],
      'password' => bcrypt($data['password']),
    ]);
  }
}
