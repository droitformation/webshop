<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbonnementRequest extends FormRequest
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
            'abo_id'         => 'required',
            'numero'         => 'required',
            'exemplaires'    => 'required',
            'user_id'     => 'required',
            'status'         => 'required',
            'renouvellement' => 'required',
        ];
    }
}
