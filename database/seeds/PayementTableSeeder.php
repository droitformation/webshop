<?php

class PayementTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('shop_payments')->truncate();

		$shop_payments = array(
			array('id' => '1','title' => 'sur facture','slug' => 'invoice','image' => 'bv.png','description' => 'Vous recevrez une facture accompagnant votre livraison.'),
			//array('id' => '2','title' => 'Carte de crédit','slug' => 'stripe','image' => 'creditcards.png','description' => 'Payement par carte de crédit')
		);

		// Uncomment the below to run the seeder
		DB::table('shop_payments')->insert($shop_payments);
	}

}
