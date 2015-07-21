<?php

class InscriptionsTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('colloque_inscriptions')->truncate();

        for ($x = 2; $x <= 11; $x++)
        {
            $inscriptions[] = [
                'colloque_id'     => 1,
                'user_id'         => $x,
                'group_id'        => null,
                'inscription_no'  => '1-2015/'.$x,
                'price_id'        => 1,
                'payed_at'        => \Carbon\Carbon::now(),
                'send_at'         => \Carbon\Carbon::now(),
                'status'          => 'pending',
                'created_at'      => \Carbon\Carbon::now(),
                'updated_at'      => \Carbon\Carbon::now()
            ];
        }

		// Uncomment the below to run the seeder
		DB::table('colloque_inscriptions')->insert($inscriptions);
	}

}
