<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class InscriptionRequest extends Request
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
            'user_id'  => 'required_without:group_id',
            'group_id' => 'required_without:user_id'
        ];
    }
}
