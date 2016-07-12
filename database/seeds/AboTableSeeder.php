<?php

class AboTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('abos')->truncate();

		$abos = array(
			array('id' => '1','title' => 'RJN','plan' => 'year','logo' => 'rjn.jpg','name' => 'Secrétariat - Formation','compte' => '','adresse' => '<p>Faculté de droit<br>Avenue du 1er-Mars 26<br>CH-2000 Neuchâtel</p>','created_at' => '2015-11-23 09:23:23','updated_at' => '2016-05-04 15:46:09','deleted_at' => NULL),
			array('id' => '2','title' => 'Droit du Bail','plan' => 'year','logo' => 'bail.jpg','name' => 'Séminaire sur le droit du bail','compte' => NULL,'adresse' => NULL,'created_at' => '2015-11-23 09:23:23','updated_at' => '2015-12-09 15:11:45','deleted_at' => NULL)
		);
		
		// Uncomment the below to run the seeder
		DB::table('abos')->insert($abos);
	}

}
