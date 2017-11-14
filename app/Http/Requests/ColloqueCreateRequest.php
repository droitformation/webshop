<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColloqueCreateRequest extends FormRequest
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
            'titre'           => 'required',
            'sujet'           => 'required',
            'organisateur'    => 'required',
            'location_id'     => 'required',
            'start_at'        => 'required',
            'registration_at' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'compte_id.required_if' => 'Le champ compte est obligatoire quand la génération d\'une facture est demandé.'
        ];
    }
}
