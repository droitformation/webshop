<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'title'      => 'required',
            'value'      => 'required_if:type,product,global',
            'product_id' => 'required_if:type,product,price,priceshipping',
            'expire_at'  => 'required|date|date_format:Y-m-d|after:yesterday',
            'type'       => 'required'
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
            'title.required'     => 'Le titre est requis',
            'value.required'     => 'La valeur en pourcent est requise',
            'expire_at.required' => 'La date de validité est requise',
            'expire_at.after'    => 'La date ne peut pas être dans le passé',
            'type.required'      => 'Le type est requis',
        ];
    }
}
