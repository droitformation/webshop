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

$factory->define(App\Droit\Inscription\Entities\Inscription::class, function (Faker\Generator $faker) {
    return [
        'colloque_id'    => $faker->numberBetween(11, 71),
        'inscription_no' => $faker->numberBetween(11, 71).'-2015/'.$faker->numberBetween(1, 5),
        'user_id'        => 1,
        'group_id'       => null,
        'price_id'       => $faker->numberBetween(200,300),
        'created_at'     => \Carbon\Carbon::now(),
        'updated_at'     => \Carbon\Carbon::now()
    ];
});

$factory->define(App\Droit\Option\Entities\Option::class, function (Faker\Generator $faker) {
    return [
        'id'          => 1,
        'colloque_id' => 1,
        'title'       => 'Option',
        'type'        => 'checkbox',
    ];
});

$factory->define(App\Droit\Option\Entities\OptionGroupe::class, function (Faker\Generator $faker){
    return [
        'id'          => 1,
        'colloque_id' => 1,
        'option_id'   => 1,
        'text'        => 'Groupe',
    ];
});

$factory->define(App\Droit\Option\Entities\OptionUser::class, function (Faker\Generator $faker){
    return [
        'user_id'          => 1,
        'option_id'        => 1,
        'inscription_id'   => 1,
        'groupe_id'        => 1,
    ];
});

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

/*
 * SHOP FACTORIES
 */

$factory->define(App\Droit\Shop\Product\Entities\Product::class, function (Faker\Generator $faker) {
    return [
        'id'              => 100,
        'title'           => 'Test product',
        'teaser'          => 'test',
        'image'           => 'test.jpg',
        'description'     => 'test' ,
        'weight'          => 900,
        'sku'             => 1,
        'price'           => 1000,
        'is_downloadable' => 0,
        'hidden'          => 0,
    ];
});

$factory->defineAs(App\Droit\Shop\Coupon\Entities\Coupon::class, 'one', function (Faker\Generator $faker) {

    $tomorrow = \Carbon\Carbon::now()->addDay();

    return [
        'value'      => '10',
        'type'       => 'general',
        'title'      => 'test',
        'product_id' => null,
        'expire_at'  => $tomorrow
    ];
});


$factory->defineAs(App\Droit\Shop\Coupon\Entities\Coupon::class, 'two', function (Faker\Generator $faker) {

    $tomorrow = \Carbon\Carbon::now()->addDay();

    return [
        'value'      => '20',
        'type'       => 'product',
        'title'      => 'second',
        'product_id' => 100,
        'expire_at'  => $tomorrow
    ];
});

$factory->defineAs(App\Droit\Shop\Coupon\Entities\Coupon::class, 'three', function (Faker\Generator $faker) {

    $tomorrow = \Carbon\Carbon::now()->addDay();

    return [
        'value'      => '0',
        'type'       => 'shipping',
        'title'      => 'freeshipping',
        'product_id' => null,
        'expire_at'  => $tomorrow
    ];
});