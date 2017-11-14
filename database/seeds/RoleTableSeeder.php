<?php

class RoleTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('roles')->truncate();

		$roles = array(
			['name' => 'Administrateur'],
			['name' => 'Contributeur']
		);

		// Uncomment the below to run the seeder
		DB::table('roles')->insert($roles);

		DB::table('user_roles')->truncate();

		$roles = array(
			['user_id' => 710, 'role_id' => 1]
		);

		// Uncomment the below to run the seeder
		DB::table('user_roles')->insert($roles);
	}

}
