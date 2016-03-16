<?php

class AuthTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testLoginWithOldPassword()
	{
		$exist = App\Droit\User\Entities\User::find(3582);

		$value = 'cindy';

		$user  = App\Droit\User\Entities\User::where('password','=', \Hash::make(md5($value)) )->get(); // \Hash::make(md5($value))

		if(!$user->isEmpty())
		{
			$user = $user->first();
			$this->assertEquals($user->id, $exist->id);
		}

	}

}
