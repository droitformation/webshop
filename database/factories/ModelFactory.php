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

$factory->define(App\Droit\Colloque\Entities\Colloque::class, function (Faker\Generator $faker) {
    return [
        'titre'           => 'My big event',
        'soustitre'       => 'Sous-titre du colloque',
        'sujet'           => 'sujet du colloque',
        'remarques'       => '',
        'start_at'        => \Carbon\Carbon::now(),
        'end_at'          => null,
        'registration_at' => \Carbon\Carbon::now(),
        'active_at'       => null,
        'organisateur'    => 'Cindy Leschaud',
        'location_id'     => 1,
        'compte_id'       => 1,
        'visible'         => 1,
        'bon'             => 1,
        'facture'         => 1,
        'adresse_id'      => 1,
    ];
});

$factory->define(App\Droit\Inscription\Entities\Inscription::class, function (Faker\Generator $faker) {
    return [
        'colloque_id'    => 39,
        'inscription_no' => $faker->numberBetween(11, 71).'-2015/'.$faker->numberBetween(1, 5),
        'user_id'        => 710,
        'group_id'       => null,
        'price_id'       => $faker->numberBetween(200,300),
        'created_at'     => \Carbon\Carbon::now(),
        'updated_at'     => \Carbon\Carbon::now()
    ];
});

$factory->define(App\Droit\Inscription\Entities\Groupe::class, function (Faker\Generator $faker) {
    return [
        'colloque_id' => 39,
        'user_id'     => 710,
        'description' => 'Une description',
        'adresse_id'  => null,
    ];
});

$factory->define(App\Droit\Option\Entities\Option::class, function (Faker\Generator $faker) {
    return [
        'colloque_id' => 1,
        'title'       => 'Option',
        'type'        => 'checkbox',
    ];
});

$factory->define(App\Droit\Price\Entities\Price::class, function (Faker\Generator $faker) {
    return [
        'colloque_id' => 1,
        'price'       => 4000,
        'type'        => 'public',
        'description' => 'test',
        'rang'        => 1,
        'remarque'    => 'test',
    ];
});

$factory->define(App\Droit\Option\Entities\OptionGroupe::class, function (Faker\Generator $faker){
    return [
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
        'groupe_id'        => null,
        'reponse'          => '',
    ];
});

$factory->define(App\Droit\Occurrence\Entities\Occurrence::class, function (Faker\Generator $faker){
    return [
        'starting_at'  => \Carbon\Carbon::now()->addDay(),
        'lieux_id'     => 1,
        'colloque_id'  => 1,
        'title'        => 'Titre'
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

$factory->define(App\Droit\Inscription\Entities\Participant::class, function (Faker\Generator $faker) {
    return [
        'name'           => 'Cindy Leschaud',
        'inscription_id' => '10-2016/2'
    ];
});

$factory->define(App\Droit\Specialisation\Entities\Specialisation::class, function (Faker\Generator $faker) {
    return [
        'title' => 'title'
    ];
});

$factory->define(App\Droit\Member\Entities\Member::class, function (Faker\Generator $faker) {
    return [
        'title' => 'title'
    ];
});

$factory->define(App\Droit\Profession\Entities\Profession::class, function (Faker\Generator $faker) {
    return [
        'title' => 'title'
    ];
});

$profession = \App::make('App\Droit\Profession\Repo\ProfessionInterface');
$canton     = \App::make('App\Droit\Canton\Repo\CantonInterface');
$pays       = \App::make('App\Droit\Pays\Repo\PaysInterface');

$professions = $profession->getAll()->pluck('title','id')->all();
$cantons     = $canton->getAll()->pluck('title','id')->all();

$factory->define(App\Droit\Adresse\Entities\Adresse::class, function (Faker\Generator $faker) use ($professions,$cantons) {
    return [
        'civilite_id'  => $faker->numberBetween(1,4),
        'first_name'   => $faker->firstName,
        'last_name'    => $faker->lastName,
        'email'        => $faker->email,
        'company'      => $faker->company,
        'profession_id' => array_rand($professions, 1),
        'telephone'    => $faker->phoneNumber,
        'mobile'       => $faker->phoneNumber,
        'fax'          => $faker->phoneNumber,
        'adresse'      => $faker->address,
        'npa'          => $faker->postcode,
        'ville'        => $faker->city,
        'canton_id'     => array_rand($cantons, 1),
        'pays_id'      => 208,
        'type'         => 1,
        'user_id'      => 0,
        'livraison'    => 1
    ];
});

/*
 * SHOP FACTORIES
 */

$factory->define(App\Droit\Shop\Product\Entities\Product::class, function (Faker\Generator $faker) {
    return [
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


$factory->define(App\Droit\Shop\Order\Entities\Order::class, function (Faker\Generator $faker) {
    return [
        'user_id'     => 1,
        'coupon_id'   => null,
        'payement_id' => 1,
        'order_no'    => '2015-00000004',
        'amount'      => 10000,
        'shipping_id' => 1,
        'onetimeurl'  => null
    ];
});

$factory->defineAs(App\Droit\Shop\Coupon\Entities\Coupon::class, 'one', function (Faker\Generator $faker) {

    $tomorrow = \Carbon\Carbon::now()->addDay();

    return [
        'id'         => 100,
        'value'      => '10',
        'type'       => 'general',
        'title'      => 'test',
        'expire_at'  => $tomorrow
    ];
});

$factory->defineAs(App\Droit\Shop\Coupon\Entities\Coupon::class, 'price', function (Faker\Generator $faker) {

    $tomorrow = \Carbon\Carbon::now()->addDay();

    return [
        'id'         => 200,
        'value'      => '20',
        'type'       => 'price',
        'title'      => 'Price minus',
        'expire_at'  => $tomorrow
    ];
});

$factory->defineAs(App\Droit\Shop\Coupon\Entities\Coupon::class, 'priceshipping', function (Faker\Generator $faker) {

    $tomorrow = \Carbon\Carbon::now()->addDay();

    return [
        'value'      => null,
        'type'       => 'priceshipping',
        'title'      => 'Price shipping',
        'expire_at'  => $tomorrow
    ];
});


$factory->define(App\Droit\Shop\Shipping\Entities\Shipping::class, function (Faker\Generator $faker) {

    return [
        'id'         => 100,
        'title'      => 'Envoi par Poste <2kg',
        'value'      => '2000',
        'price'      => '1000',
        'type'       => 'poids',
    ];
});

$factory->define(App\Droit\Shop\Cart\Entities\Cart::class, function (Faker\Generator $faker) {

    return [
        'id'        => 1,
        'user_id'   => 1,
        'coupon_id' => null,
        'cart'      => serialize([1,2,3]),
    ];
});

$factory->defineAs(App\Droit\Shop\Coupon\Entities\Coupon::class, 'two', function (Faker\Generator $faker) {

    $tomorrow = \Carbon\Carbon::now()->addDay();

    return [
        'id'         => 200,
        'value'      => '20',
        'type'       => 'product',
        'title'      => 'second',
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

$factory->define(App\Droit\Author\Entities\Author::class, function (Faker\Generator $faker) {
    return [
        'first_name' => 'Cindy',
        'last_name'  => 'Leschaud',
        'occupation' => 'Webmaster',
        'bio'        => 'Test',
        'photo'      => 'cindy.jpg',
        'rang'       => 1
    ];
});

$factory->define(App\Droit\Arret\Entities\Arret::class, function (Faker\Generator $faker) {
    return [
        'site_id'    => 1,
        'reference'  => 'reference 123',
        'pub_date'   => \Carbon\Carbon::now(),
        'abstract'   => 'lorem ipsum dolor amet',
        'pub_text'   => 'amet dolor ipsum lorem'
    ];
});

$factory->define(App\Droit\Analyse\Entities\Analyse::class, function (Faker\Generator $faker) {
    return [
        'site_id'    => 1,
        'title'      => 'Un titre',
        'pub_date'   => \Carbon\Carbon::now(),
        'abstract'   => 'lorem ipsum dolor amet',
    ];
});

$factory->define(App\Droit\Categorie\Entities\Categorie::class, function (Faker\Generator $faker) {
    return [
        'site_id' => 1,
        'title'   => 'Un titre',
        'image'   => 'lorex.jpg',
    ];
});

$factory->define(App\Droit\Author\Entities\Author::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name'  =>  $faker->lastName,
        'occupation' => $faker->sentence,
        'bio'        => $faker->sentence,
        'photo'      => 'test.jpg'
    ];
});

$factory->define(App\Droit\Compte\Entities\Compte::class, function (Faker\Generator $faker) {
    return [
        'motif'   => 'Payement',
        'adresse' => 'Université de Neuchâtel<br/>Service des fonds de tiers<br/>2000 Neuchâtel',
        'compte'  => '20-4130-2',
    ];
});

$factory->define(App\Droit\Abo\Entities\Abo::class, function (Faker\Generator $faker) {
    return [
        'title' => 'Test',
        'plan'  => 'month',
        'logo'  => 'logo.png',
        'name'  => 'Test'
    ];
});

$factory->define(App\Droit\Abo\Entities\Abo_users::class, function (Faker\Generator $faker) {
    return [
        'abo_id'         => 1,
        'numero'         => 123,
        'exemplaires'    => 1,
        'adresse_id'     => '1',
        'tiers_id'       => null,
        'price'          => null,
        'reference'      => null,
        'remarque'       => null,
        'status'         => 'abonne',
        'renouvellement' => 'auto'
    ];
});

$factory->define(App\Droit\Abo\Entities\Abo_factures::class, function (Faker\Generator $faker) {
    return [
        'abo_user_id' => 1,
        'product_id'  => 1,
        'payed_at'    => null
    ];
});

$factory->define(App\Droit\Shop\Attribute\Entities\Attribute::class, function (Faker\Generator $faker) {
    return [
        'title' => 'REF'
    ];
});

$factory->define(App\Droit\Document\Entities\Document::class, function (Faker\Generator $faker) {
    return [
        'colloque_id' => 1,
        'display'     => 1,
        'type'        => 'illustration',// 'illustration', 'programme', 'document'
        'path'        => 'img.jpg',
        'titre'       => 'Vignette'
    ];
});

$factory->define(App\Droit\Sondage\Entities\Sondage::class, function (Faker\Generator $faker) {
    return [
        'colloque_id'  => 1,
        'valid_at'     => 1
    ];
});


$factory->define(App\Droit\Sondage\Entities\Avis::class, function (Faker\Generator $faker) {
    return [
        'type' => 'text',
        'question' => '',
        'choices' => null
    ];
});


$factory->defineAs(App\Droit\User\Entities\User::class, 'admin' ,function ($factory){
    return [
        'first_name' => 'Cindy',
        'last_name'  => 'Leschaud',
        'email'      => 'cindy.leschaud@unine.ch',
        'password'   => bcrypt('cindy2')
    ];
});

$factory->defineAs(App\Droit\User\Entities\User::class, 'user' ,function ($factory){
    return [
        'first_name' => 'Jane',
        'last_name'  => 'Doe',
        'email'      => 'jane.doe@gmail.com',
        'password'   => bcrypt('jane2')
    ];
});

$factory->define(App\Droit\Reminder\Entities\Reminder::class, function (Faker\Generator $faker) {

    return [
        'send_at'  => \Carbon\Carbon::now()->addMonth(1)->toDateString(),
        'start'    => 'start_at',
        'title'    => 'Rappel pour le colloque',
        'text'     => 'Dapibus ante accumasa laoreet mauris nostra toittis énis molestie vehicula non interdùm, vehiculâ suscipit alèquam. Lorem ad quîs j\'libéro pharétra vivamus mollis ultricités ut, vulputaté ac pulvinar èst commodo aenanm pharétra cubilia, lacus aenean pharetra des ïd quisquées mauris varius sit. Mie dictumst nûllam çurcus molestié imperdiet vestibulum suspendisse tempor habitant sit pélléntésque sit çunc, primiés ?',
        'type'     => 'colloque',
        'duration' => 'month',
        'model_id' => 39,
        'model'    => 'App\Droit\Colloque\Entities\Colloque',
        'relation'    => null,
        'relation_id' => null,
    ];
});
