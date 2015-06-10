<?php

class ShippingTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('shop_shipping')->truncate();

		$shop_shipping = array(
			[
                'title' => 'Envoi par Poste <2kg',
                'value' => '2000',
                'price' => '1000',
                'type'  => 'poids'
            ],
            [
                'title' => 'Envoi par Poste <5kg',
                'value' => '5000',
                'price' => '1100',
                'type'  => 'poids'
            ],
            [
                'title' => 'Envoi par Poste <10kg',
                'value' => '10000',
                'price' => '1400',
                'type'  => 'poids'
            ],
            [
                'title' => 'Envoi par Poste <20kg',
                'value' => '20000',
                'price' => '1900',
                'type'  => 'poids'
            ],
            [
                'title' => 'Envoi par Poste <30kg',
                'value' => '30000',
                'price' => '2600',
                'type'  => 'poids'
            ],
            [
                'title' => 'Frais de port gratuit',
                'value' => '0',
                'price' => '0',
                'type'  => 'gratuit'
            ],
		);

		// Uncomment the below to run the seeder
		DB::table('shop_shipping')->insert($shop_shipping);
	}

}
