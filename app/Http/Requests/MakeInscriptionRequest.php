<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MakeInscriptionRequest extends Request
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
            'user_id'     => 'required',
            'colloque_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'user_id.required'     => 'Un détenteur est requis',
            'colloque_id.required' => 'Le colloque est requis'
        ];
    }
}
