<?php

class RoleTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('roles')->truncate();

		$roles = array(
			array('id' => '1','name' => 'Administrateur'),
			array('id' => '2','name' => 'Contributeur')
		);

		// Uncomment the below to run the seeder
		DB::table('roles')->insert($roles);
	}

}
