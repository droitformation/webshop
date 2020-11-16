<?php namespace Droitformation\Registry\Facades;

use Illuminate\Support\Facades\Facade;

class Registry extends Facade {

	/**
	 * Get the alias
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'registry'; }

}
