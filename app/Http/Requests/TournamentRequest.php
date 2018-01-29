<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TournamentRequest extends FormRequest
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
            'name' => 'required',
            'rounds' => 'required|integer',
            'minplayers' => 'required|integer',
            'maxplayers' => 'nullable|integer',
            'startdate' => 'required|date|after:yesterday',
            'streetname' => '',
            'housenumber' => '',
            'zipcode' => '',
            'city' => '',
            'description' => '',
            'game' => 'required|exists:boardgames,id',
            'users' => 'required|array|exists:users,id',
            'private' => '',
        ];
    }
}
