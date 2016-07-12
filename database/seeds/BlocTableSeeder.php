<?php

class BlocTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('blocs')->truncate();

		$blocs = array(
			array('id' => '1','title' => 'Commentaire pratique','content' => '','image' => 'commentaire-pratique.jpg','url' => 'http://www.publications-droit.ch/#/cat/publications/item/294','rang' => '2','type' => 'pub','position' => 'sidebar','created_at' => '2016-02-02 17:27:11','updated_at' => '2016-02-02 17:27:11','deleted_at' => NULL),
			array('id' => '2','title' => '','content' => '','image' => 'logo-helbing.png','url' => 'http://www.helbing.ch/home?CSPCHD=00000100000033r913Q4G00000I5bwXxLdLvW$BifpoIyG8g--','rang' => '1','type' => 'soutien','position' => 'sidebar','created_at' => '2016-02-02 17:27:22','updated_at' => '2016-02-02 17:27:22','deleted_at' => NULL),
			array('id' => '3','title' => '','content' => '','image' => 'hlv-logo.png','url' => 'http://www.helbing.ch/home','rang' => '1','type' => 'soutien','position' => 'sidebar','created_at' => '2016-02-02 10:46:05','updated_at' => '2016-02-02 10:46:05','deleted_at' => NULL),
			array('id' => '4','title' => 'Commentaire pratique','content' => '<p>Ce nouveau commentaire a pour but de répondre rapidement et de manière 
concrète aux problèmes rencontrés dans la pratique du droit du bail.</p><p><a target="_blank" href="http://www.publications-droit.ch/#/cat/publications/item/294">Commander</a><br></p>','image' => 'droit-bail-a-loyer-01.jpg','url' => 'http://www.publications-droit.ch/#/cat/publications/item/294','rang' => '2','type' => 'pub','position' => 'sidebar','created_at' => '2016-02-02 17:14:40','updated_at' => '2016-02-02 17:14:40','deleted_at' => '2016-02-02 17:14:40')
		);

		// Uncomment the below to run the seeder
		DB::table('blocs')->insert($blocs);
	}

}
