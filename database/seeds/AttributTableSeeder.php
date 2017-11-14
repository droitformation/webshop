<?php

class AttributTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('shop_attributes')->truncate();

		$shop_attributes = array(
			array('id' => '1','title' => 'ISBN','reminder' => NULL,'text' => NULL,'duration' => '','deleted_at' => NULL),
			array('id' => '2','title' => 'ISSN','reminder' => NULL,'text' => NULL,'duration' => '','deleted_at' => NULL),
			array('id' => '3','title' => 'Référence','reminder' => NULL,'text' => NULL,'duration' => '','deleted_at' => NULL),
			array('id' => '4','title' => 'Édition','reminder' => NULL,'text' => NULL,'duration' => '','deleted_at' => NULL)
		);

		// Uncomment the below to run the seeder
		DB::table('shop_attributes')->insert($shop_attributes);
	}

}
