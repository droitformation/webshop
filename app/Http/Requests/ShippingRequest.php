<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ShippingRequest extends Request
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
            'title'     => 'required',
            'value'     => 'required',
            'price'     => 'required',
            'type'      => 'required'
        ];
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Le titre est requis',
            'value.required' => 'La valeur en pourcent est requise',
            'price.required' => 'Le prix est requis',
            'type.required'  => 'Le type est requis',
        ];
    }

}
