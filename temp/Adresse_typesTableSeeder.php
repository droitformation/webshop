<?php

class Adresse_typesTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('adresse_types')->truncate();

		$adresse_types = array(
			array('type' => 'Contact' ),
            array('type' => 'Privé' ),
            array('type' => 'Professionnelle' )
		);

		// Uncomment the below to run the seeder
		DB::table('adresse_types')->insert($adresse_types);
	}

}
