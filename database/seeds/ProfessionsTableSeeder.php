<?php

class ProfessionsTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('professions')->truncate();

		$professions = array(
			array('title' => 'Avocat' ),
			array('title' => 'Juriste'),
			array('title' => 'Greffier'),
			array('title' => 'Assistant'),
			array('title' => 'Avocat-stagiaire'),
			array('title' => 'Chargé d\'enseignement')
		);

		// Uncomment the below to run the seeder
		DB::table('professions')->insert($professions);
	}

}
