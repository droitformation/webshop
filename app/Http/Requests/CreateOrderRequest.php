<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateOrderRequest extends Request
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
        $rules = [
            'user_id' => 'required_without|',
        ];

        foreach($this->request->get('order.products') as $key => $val)
        {
            $rules['order.products.product'] = 'required|numeric';
            $rules['order.products.qty']     = 'required|numeric';
            $rules['order.products.rabais']  = 'numeric';
        }

        return $rules;
    }
}
