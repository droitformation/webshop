<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateUser extends Request {

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
			'first_name' => 'required_without:company',
			'last_name'  => 'required_without:company',
			'company'    => 'required_without_all:first_name,last_name',
            'email'      => 'required',
        ];
	}

	public function messages()
	{
		return [
			'first_name.required_without'  => 'Un prénom est requis sans nom d\'entreprise',
			'last_name.required_without'   => 'Un nom est requis sans nom d\'entreprise',
			'company.required_without_all' => 'Une nom d\'entreprise est requis sans nom/prénom',
		];
	}
}
