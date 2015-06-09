<?php

class ShippingTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('shop_shipping')->truncate();

		$professions = array(
			[
                'title' => 'Envoi Courier B',
                'value' => '',
                'price' => '',
                'type'  => 'poids'
            ],

		);

		// Uncomment the below to run the seeder
		DB::table('shop_shipping')->insert($professions);
	}

}
