<?php

class SiteTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('sites')->truncate();

		$sites = array(
			array(
				'id' => '1',
				'nom' => 'Publications-droit.ch',
				'url' => 'http://www.publications-droit.ch/',
				'logo' => 'pubdroit.svg',
				'slug' => 'pubdroit',
				'prefix' => 'pubdroit',
				'created_at' => '2015-11-10 08:00:00',
				'updated_at' => '2015-11-10 08:00:00',
				'deleted_at' => NULL),
			array(
				'id' => '2',
				'nom' => 'Bail.ch',
				'url' => 'http://www.bail.ch',
				'logo' => 'bail.svg',
				'slug' => 'bail',
				'prefix' => 'bail',
				'created_at' => '2015-11-10 08:23:23',
				'updated_at' => '2015-11-10 08:23:23',
				'deleted_at' => NULL),
			array(
				'id' => '3',
				'nom' => 'Droit matrimonial',
				'url' => 'http://www.droitmatrimonial.ch',
				'logo' => 'droitmatrimonial.svg',
				'slug' => 'matrimonial',
				'prefix' => 'matrimonial',
				'created_at' => '2015-11-10 08:00:00',
				'updated_at' => '2015-11-10 08:00:00',
				'deleted_at' => NULL)
		);
		
		// Uncomment the below to run the seeder
		DB::table('sites')->insert($sites);
	}

}
