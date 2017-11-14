<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBloc extends FormRequest
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
            'title'     => 'required_without_all:content,url,file',
            'content'   => 'required_without_all:title,url,file',
            'file'      => 'required_with:url',
            'url'       => 'required_with:file',
            'page_id'   => 'required',
            'type'      => 'required',
            'position'  => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required_without_all'   => 'Le titre est requis sans autre champ',
            'content.required_without_all' => 'Un contenu est requise sans autre champ',
            'file.required_with'           => 'Une image est requise avec un lien',
            'url.required_without'         => 'Une image est requise avec un lien',
            'page_id.required'             => 'Au moins une page est requise',
        ];
    }
}
