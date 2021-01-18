<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(App\Droit\User\Entities\User::class)->create([
            'first_name' => 'Droit',
            'last_name'  => 'Formation',
            'email'      => 'droitformation.web@gmail.com',
            'password'   => bcrypt('cindy2')
        ]);

        $user->roles()->attach(1);

        $adresse = factory(App\Droit\Adresse\Entities\Adresse::class)->create([
            'civilite_id'   => 2,
            'first_name'    => 'Droit',
            'last_name'     => 'Formation',
            'email'         => 'droitformation.web@gmail.com',
            'company'       => 'UniNE',
            'profession_id' => 1,
            'telephone'     => '032 690 00 23',
            'mobile'        => '032 690 00 23',
            'fax'           => null,
            'adresse'       => 'Ruelle de l\'hôtel de ville 3',
            'npa'           => '2520',
            'ville'         => 'La Neuveville',
            'canton_id'     => 6,
            'pays_id'       => 208,
            'type'         => 1,
            'user_id'      => $user->id,
            'livraison'    => 1
        ]);

        $user->adresses()->save($adresse);
    }
}
