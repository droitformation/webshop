<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StockChangeRequest extends Request
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
            'product_id' => 'required',
            'amount'     => 'required',
            'motif'      => 'required'
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'Un nombre est requis',
            'motif.required'  => 'Un motif'
        ];
    }
}
