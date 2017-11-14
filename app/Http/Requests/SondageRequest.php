<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SondageRequest extends FormRequest
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
            'valid_at'    => 'required',
            'title'       => 'required_if:marketing,1',
            'description' => 'required_if:marketing,1',
            'colloque_id' => 'required_unless:marketing,1'
        ];
    }
}
