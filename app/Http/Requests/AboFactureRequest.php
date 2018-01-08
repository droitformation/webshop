<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class AboFactureRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'abo_user_id' => [
                Rule::unique('abo_factures')->where(function ($query) use ($request) {
                    $query->where('abo_user_id', $request->get('abo_user_id'));
                    $query->where('product_id', $request->get('product_id'));
                    $query->whereNull('deleted_at');
                })
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'abo_user_id.unique' => 'Une facture existe déjà pour cette édition'
        ];
    }
}
