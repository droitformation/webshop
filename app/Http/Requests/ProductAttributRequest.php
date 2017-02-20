<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductAttributRequest extends FormRequest
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
            'attribute_id' => 'required',
            'value' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'attribute_id.required' => 'Veuillez sélectionner un attribut dans la liste',
            'value.required' => 'Le champ valeur est obligatoire'
        ];
    }
}
