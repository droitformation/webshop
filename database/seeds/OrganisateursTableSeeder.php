<?php

class OrganisateursTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('organisateurs')->truncate();

		$organisateurs = [
            [
                'name'        => 'Faculté de droit',
                'description' => 'La Faculté de droit de l\'Université de Neuchâtel',
                'url'         => 'http://www2.unine.ch/droit',
                'logo'        => 'facdroit.jpg',
            ],
			[
                'name'        => 'CEMAJ',
                'description' => 'Centre de recherche sur les modes amiables et juridictionnels de gestion des conflits',
                'url'         => 'https://www2.unine.ch/cemaj',
                'logo'        => 'cemaj.png',
            ],
            [
                'name'        => 'CERT',
                'description' => 'Centre d\'étude des relations de travail',
                'url'         => 'https://www2.unine.ch/cert',
                'logo'        => 'cert.png',
            ]
		];

		// Uncomment the below to run the seeder
		DB::table('organisateurs')->insert($organisateurs);
	}
}
