<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateOrganisateur extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (\Auth::check())
        {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'    => 'required',
            'adresse' => 'required_if:centre,1',
            'file'    => 'required_if:centre,1|mimes:jpeg,jpg,gif,png'
        ];
    }
}
