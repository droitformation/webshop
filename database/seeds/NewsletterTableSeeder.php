<?php

class NewsletterTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('newsletters')->truncate();

		$newsletters = array(
			array('id' => '1','titre' => 'Publications-droit.ch','site_id' => '1','list_id' => '1499252','from_name' => 'Faculté de droit Neuchâtel','from_email' => 'info@publications-droit.ch','return_email' => 'bounce@publications-droit.ch','unsuscribe' => 'unsubscribe','preview' => 'http://publications-droit.ch','logos' => 'header-pubdroit.png','header' => 'banner-pubdroit.png','soutien' => NULL,'color' => '#1A446E','created_at' => '2011-08-31 22:00:00','updated_at' => '2016-01-20 07:37:53','deleted_at' => NULL),
			array('id' => '2','titre' => 'Bail.ch','site_id' => '2','list_id' => '2','from_name' => 'Bail.ch','from_email' => 'info@bail.ch','return_email' => 'bounce@bail.ch','unsuscribe' => 'unsuscribe','preview' => 'http://bail.ch','logos' => 'headerBail.jpg','header' => 'header-bail.jpg','soutien' => NULL,'color' => '#cb2629','created_at' => '2011-08-31 22:00:00','updated_at' => '2011-08-31 22:00:00','deleted_at' => NULL),
			array('id' => '3','titre' => 'Droit matrimonial','site_id' => '3','list_id' => '3','from_name' => 'Droit matrimonial','from_email' => 'info@droitmatrimonial.ch','return_email' => 'bounce@droitmatrimonial.ch','unsuscribe' => 'unsuscribe','preview' => 'http://droitmatrimonial.ch','logos' => 'headerDroitMatrimonial.jpg','header' => 'imageDroitMatrimonial.jpg','soutien' => NULL,'color' => '#f39','created_at' => '2011-08-31 22:00:00','updated_at' => '2011-08-31 22:00:00','deleted_at' => NULL)
		);


		// Uncomment the below to run the seeder
		DB::table('newsletters')->insert($newsletters);
	}

}
