php<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RegisterRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return Auth::guest();
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'firstname' => 'required|max:190',
      'insertion' => 'nullable|max:20',
      'lastname' => 'required|max:190',
      'city' => 'nullable|max:100',
      'username' => 'required|unique:users|max:190',
      'email' => 'required|unique:users|max:190|email',
      'password' => 'confirmed|required',
      'image' => 'image'
    ];
  }
}
