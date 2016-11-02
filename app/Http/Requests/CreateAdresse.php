<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateAdresse extends Request {

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
			'company'    => 'required_without_all:first_name,last_name',
            'first_name' => 'required_without:company',
            'last_name'  => 'required_without:company',
            'adresse'    => 'required',
			'npa'        => 'required',
			'ville'      => 'required',
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
			'company.required_without_all' => 'Le nom de l\'entreprise est requis sans prénom/nom',
			'first_name.required_without'  => 'Le prénom est requis dans nom d\'entreprise ',
			'last_name.required_without'  => 'Le nom est requis dans nom d\'entreprise ',
		];
	}

}
