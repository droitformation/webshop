<?php

class MenuTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('menus')->truncate();

		$menus = array(
			array('id' => '1','title' => 'Menu principal bail.ch','position' => 'main','site_id' => '2'),
			array('id' => '2','title' => 'Barre latérale gauche Bail','position' => 'sidebar','site_id' => '2'),
			array('id' => '3','title' => 'Menu principal Droit matrimonail','position' => 'main','site_id' => '3'),
			array('id' => '4','title' => 'Barre latérale Droit matrimonial','position' => 'sidebar','site_id' => '3'),
			array('id' => '5','title' => 'Menu principal Publications-droit','position' => 'main','site_id' => '1')
		);

		// Uncomment the below to run the seeder
		DB::table('menus')->insert($menus);
	}

}
