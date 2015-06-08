<?php

class CivilitesTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('civilites')->truncate();

		$civilites = array(
			array('title' => 'Monsieur'),
			array('title' => 'Madame'),
			array('title' => 'Me'),
			array('title' => ' ')
		);

		// Uncomment the below to run the seeder
		DB::table('civilites')->insert($civilites);
	}

}
