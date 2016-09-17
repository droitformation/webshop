<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AboRequest extends Request
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
            'title'       => 'required',
            'price'       => 'required',
            'plan'        => 'required',
            'products_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required'       => 'Le titre est requis',
            'price.required'       => 'Le prix est requis',
            'plan.required'        => 'Indiquez une Récurrence',
            'products_id.required' => 'Indiquez au moins un livre',
        ];
    }
}
