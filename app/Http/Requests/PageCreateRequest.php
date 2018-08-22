<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PageCreateRequest extends Request
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
            'title'      => 'required_without:isExternal,url',
            'template'   => 'required_without:isExternal,url',
            'isExternal' => 'required_with:url',
            //'url'        => 'required_if:isExternal,1|url',
            'site_id'    => 'required',
            'menu_title' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required_without' => 'Le titre est requis si ceci n\'est pas un lien externe',
            'url.required_with'      => 'Une url est requise si ceci est un lien externe',
            'site_id.required'       => 'Indiquer à quel site cette page/lien appartient',
            'menu_id.required'       => 'Indiquer à quel menu cette page/lien appartient',
            'menu_title.required'    => 'Indiquer le titre du lien dans le menu',
        ];
    }
}
