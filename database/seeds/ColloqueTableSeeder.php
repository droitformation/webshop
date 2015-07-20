<?php

class ColloqueTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('colloques')->truncate();

		$colloques = [
			[
                'title'           => 'Curae éuismod quam ultrûcéas',
                'soustitre'       => 'Platea sociosqu potentié proîn',
                'sujet'           => 'Est-a-dire curabitur lorem fermentum potenti',
                'remarques'       => 'Frînglilia porttitor curabitur proin est èiam convallis léo tincidunt ût ac métus vestibulum elementum consequat pulvinar.',
                'start_at'        => \Carbon\Carbon::now()->addWeek(3),
                'end_at'          => \Carbon\Carbon::now()->addWeek(3),
                'registration_at' => \Carbon\Carbon::now()->addWeek(2),
                'active_at'       => \Carbon\Carbon::now()->addMonth(1),
                'organisateur_id' => '1',
                'location_id'     => '1',
                'compte_id'       => '1',
                'visible'         => '0',
                'bon'             => '1',
                'facture'         => '1',
                'created_at'      => \Carbon\Carbon::now(),
                'updated_at'      => \Carbon\Carbon::now(),
            ]
		];

		DB::table('colloques')->insert($colloques);
	}
}
