<?php

class UsersTableSeeder extends \Illuminate\Database\Seeder  {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('adresses')->truncate();
        DB::table('users')->truncate();

        $me = factory(App\Droit\User\Entities\User::class)->create([
            'first_name' => 'Cindy',
            'last_name'  => 'Leschaud',
            'email'      => 'email',
            'password'   => bcrypt('cindy2')
        ]);

        $repo = \App::make('App\Droit\Adresse\Repo\AdresseInterface');

        $adresse = $repo->create(array(
            'civilite_id'   => 2,
            'first_name'    => 'Cindy',
            'last_name'     => 'Leschaud',
            'email'         => 'cindy.leschaud@unine.ch',
            'company'       => 'Unine',
            'role'          => '',
            'profession_id' => 1,
            'telephone'     => '032 751 38 07',
            'mobile'        => '078 690 00 23',
            'fax'           => '',
            'adresse'       => 'Ruelle de l\'hôtel de ville 3',
            'cp'            => '',
            'complement'    => '',
            'npa'           => '2520',
            'ville'         => 'La Neuveville',
            'canton_id'     => 6,
            'pays_id'       => 208,
            'type'          => 1,
            'user_id'       => $me->id,
            'livraison'     => 1,
        ));

        $users = factory(App\Droit\User\Entities\User::class, 10)->create();

        foreach($users as $user)
        {
            $addresse = factory(App\Droit\Adresse\Entities\Adresse::class)->create([
                'user_id'    => $user->id,
                'first_name' => $user->firstName,
                'last_name'  => $user->lastName,
                'email'      => $user->email,
            ]);
        }

		// Uncomment the below to run the seeder
		//DB::table('users')->insert($users);
	}

}
