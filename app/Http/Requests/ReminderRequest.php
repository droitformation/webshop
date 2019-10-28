<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReminderRequest extends FormRequest
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
            'title'    => 'required',
            'model_id' => 'required',
            'type'     => 'required',
            'start'    => 'required',
            'duration' => 'required',
            'model'    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'duration.required'  => 'L\'interval de temps est obligatoire',
            'model_id.required'  => 'Un item est requis',
        ];
    }
}
