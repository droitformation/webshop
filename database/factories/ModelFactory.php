<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Droit\User\Entities\User::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name'  => $faker->lastName,
        'email'      => $faker->email,
        'password'   => bcrypt(str_random(10))
    ];
});

$factory->define(App\Droit\Adresse\Entities\Adresse::class, function (Faker\Generator $faker) {
    return [
        'civilite_id'  => $faker->numberBetween(1,3),
        'first_name'   => $faker->firstName,
        'last_name'    => $faker->lastName,
        'email'        => $faker->email,
        'company'      => $faker->company,
        'profession_id'=> $faker->numberBetween(1,4),
        'telephone'    => $faker->phoneNumber,
        'mobile'       => $faker->phoneNumber,
        'fax'          => $faker->phoneNumber,
        'adresse'      => $faker->address,
        'npa'          => $faker->postcode,
        'ville'        => $faker->city,
        'canton_id'    => $faker->numberBetween(1,26),
        'pays_id'      => 208,
        'type'         => 1,
        'user_id'      => 1,
        'livraison'    => 1
    ];
});

