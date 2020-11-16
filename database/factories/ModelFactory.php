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

$factory->define(App\Droit\Inscription\Entities\Rabais::class, function (Faker\Generator $faker) {
    return [
        'value'     => $faker->numberBetween(200,300),
        'title'     => $faker->name,
        'expire_at' => \Carbon\Carbon::now()->addDay()
    ];
});

$factory->define(App\Droit\Calculette\Entities\Calculette_ipc::class, function (Faker\Generator $faker) {
    
    $date = \Carbon\Carbon::now()->addMonth()->format('Y-m-d');
    
    return [
        'indice'   => 3,
        'start_at' => $date
    ];
});

$factory->define(App\Droit\Calculette\Entities\Calculette_taux::class, function (Faker\Generator $faker) {

    $date = \Carbon\Carbon::now()->addMonth()->format('Y-m-d');

    return [
        'taux'     => 3,
        'canton'   => 'be',
        'start_at' => $date
    ];
});

$factory->define(App\Droit\Inscription\Entities\Groupe::class, function (Faker\Generator $faker) {
    return [
        'colloque_id' => 39,
        'user_id'     => 710
    ];
});

$factory->define(App\Droit\Inscription\Entities\Rappel::class, function (Faker\Generator $faker) {
    return [
        'user_id'        => 1,
        'group_id'       => null,
        'inscription_id' => 1,
        'colloque_id'    => 1,
        'montant'        => null
    ];
});

$factory->define(App\Droit\Colloque\Entities\Colloque_attestation::class, function (Faker\Generator $faker) {
    return [
        'colloque_id'  => 1,
        'telephone'    => $faker->phoneNumber,
        'lieu'         => $faker->sentence,
        'organisateur' => $faker->sentence,
        'title'        => $faker->sentence,
        'signature'    => $faker->name,
        'comment'      => $faker->sentence,
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

$factory->define(App\Droit\PriceLink\Entities\PriceLink::class, function (Faker\Generator $faker) {
    return [
        'price'       => 4000,
        'type'        => 'public',
        'description' => 'test',
        'rang'        => 1,
        'remarque'    => 'test',
    ];
});

$factory->define(App\Droit\Location\Entities\Location::class, function (Faker\Generator $faker) {
    return [
        'name'    => 'The location',
        'adresse' => 'Ruelle de l\'hôtel de ville 3',
        'url'     => null,
        'map'     => 'test.jpg',
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
        'password'   => bcrypt(\Str::random(10))
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
        'title' => $faker->word
    ];
});

$factory->define(App\Droit\Member\Entities\Member::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->word
    ];
});

$factory->define(App\Droit\Site\Entities\Site::class, function (Faker\Generator $faker) {
    return [
        'nom'    => $faker->word,
        'url'    => $faker->url,
        'logo'   => $faker->word,
        'slug'   => $faker->word,
        'prefix' => $faker->word
    ];
});

$factory->define(App\Droit\Menu\Entities\Menu::class, function (Faker\Generator $faker) {
    return [
        'title'    => $faker->word,
        'position' => 'main',
        'site_id'  => 2
    ];
});

$factory->define(App\Droit\Seminaire\Entities\Seminaire::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->word,
        'year'  => $faker->year
    ];
});

$factory->define(App\Droit\Seminaire\Entities\Subject::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->word,
        'rang'  => $faker->numberBetween(1,10),
        'file'  => 'test.pdf',
        'appendixes'  => 'test1.pdf, test2.pdf'
    ];
});

$factory->define(App\Droit\Location\Entities\Location::class, function (Faker\Generator $faker) {
    return [
        'name'    => 'Un lieux',
        'adresse' => '<p>Une adresse</p>',
    ];
});

$factory->define(App\Droit\Profession\Entities\Profession::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->word
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

$factory->define(App\Droit\Shop\Coupon\Entities\Coupon::class, function (Faker\Generator $faker) {

    $tomorrow = \Carbon\Carbon::now()->addDay();

    return [
        'value'      => '10',
        'type'       => 'global',
        'title'      => 'test',
        'expire_at'  => $tomorrow
    ];
});

/*$factory->defineAs(App\Droit\Shop\Coupon\Entities\Coupon::class, 'price', function (Faker\Generator $faker) {

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
});*/

$factory->define(App\Droit\Shop\Rappel\Entities\Rappel::class, function (Faker\Generator $faker) {
    return [
        'order_id'  => 1
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

/*$factory->defineAs(App\Droit\Shop\Coupon\Entities\Coupon::class, 'two', function (Faker\Generator $faker) {

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
});*/

$factory->define(App\Droit\Shop\Categorie\Entities\Categorie::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->word,
        'image' => $faker->word.'.jpg',
        'rang' => 1,
    ];
});

$factory->define(App\Droit\Author\Entities\Author::class, function (Faker\Generator $faker) {
    return [
        'first_name' => 'Cindy',
        'last_name'  => 'Leschaud',
        'occupation' => 'Webmaster',
        'bio'        => 'Test',
        'photo'      => 'cindy.jpg',
        'rang'       => 1,
        'site_id'    => 1
    ];
});

$factory->define(App\Droit\Shop\Author\Entities\Author::class, function (Faker\Generator $faker) {
    return [
        'first_name' => 'Cindy',
        'last_name'  => 'Leschaud'
    ];
});


$factory->define(App\Droit\Organisateur\Entities\Organisateur::class, function (Faker\Generator $faker) {
    return [
        'name' => 'Mon organisateur',
        'description' => 'Lorem ipsum',
        'url' => '',
        'logo' => 'facdroit.jpg',
        'centre' => 1,
        'tva' => '',
        'adresse' => 'Avenue du test 24',
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
        'last_name'  => $faker->lastName,
        'occupation' => $faker->sentence,
        'bio'        => $faker->sentence,
        'photo'      => 'test.jpg'
    ];
});

$factory->define(App\Droit\Domain\Entities\Domain::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->word
    ];
});

$factory->define(App\Droit\Compte\Entities\Compte::class, function (Faker\Generator $faker) {
    return [
        'motif'   => 'Payement',
        'adresse' => 'Université de Neuchâtel<br/>Service des fonds de tiers<br/>2000 Neuchâtel',
        'centre'  => 'U.12345',
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
        'user_id'        => null,
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

$factory->define(App\Droit\Abo\Entities\Abo_rappels::class, function (Faker\Generator $faker) {
    return [
        'abo_facture_id' => 1
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


/*$factory->defineAs(App\Droit\User\Entities\User::class, 'admin' ,function ($factory){
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
});*/

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


$factory->define(App\Droit\Page\Entities\Page::class, function (Faker\Generator $faker) {
    return [
        'title'       => $faker->sentence,
        'content'     => $faker->sentence,
        'template'    => 'page',
        'site_id'     => 2,
        'menu_title'  => $faker->word,
        'slug'        => $faker->word,
        'rang'        => 1,
        'menu_id'     => null,
        'parent_id'   => null,
        'url'         => null,
        'isExternal'  => null,
        'hidden'      => null,
    ];
});

$factory->define(App\Droit\Bloc\Entities\Bloc::class,function (Faker\Generator $faker){
    return [
        'title'       => $faker->sentence,
        'content'     => $faker->sentence,
        'image'      => '',
        'url'        => '',
        'rang'       => 1,
        'type'       => 'pub',
        'site_id'    => 2,
        'position'   => 'sidebar'
    ];
});

/**
 * Newsletter
 */

$factory->define(App\Droit\Newsletter\Entities\Newsletter_users::class, function (Faker\Generator $faker) {
    return [
        'email'        => $faker->email,
        'activation_token' => '1234',
        'activated_at' => date('Y-m-d G:i:s')
    ];
});

$factory->define(App\Droit\Newsletter\Entities\Newsletter_subscriptions::class, function (Faker\Generator $faker) {
    return [
        'user_id'       => 1,
        'newsletter_id' => 1
    ];
});

$factory->define(App\Droit\Newsletter\Entities\Newsletter::class, function (Faker\Generator $faker) {
    return [
        'titre'        => 'Titre',
        'list_id'      => '1',
        'from_name'    => 'Nom',
        'from_email'   => 'cindy.leschaud@gmail.com',
        'return_email' => 'cindy.leschaud@gmail.com',
        'unsuscribe'   => 'unsubscribe',
        'preview'      => 'droit.local',
        'logos'        => 'logos.jpg',
        'header'       => 'header.jpg',
        'color'        => '#fff'
    ];
});

$factory->define(App\Droit\Newsletter\Entities\Newsletter_campagnes::class, function (Faker\Generator $faker) {
    return [
        'sujet'           => 'Sujet',
        'auteurs'         => 'Cindy Leschaud',
        'status'          => 'Brouillon',
        'newsletter_id'   => 1,
        'api_campagne_id' => 1,
        'send_at'         => null,
        'created_at'      => \Carbon\Carbon::createFromDate(2016, 12, 21)->toDateTimeString(),
        'updated_at'      => \Carbon\Carbon::createFromDate(2016, 12, 21)->toDateTimeString(),
    ];
});

/*$factory->defineAs(App\Droit\User\Entities\User::class, 'admin' ,function ($factory){
    return [
        'name'       => 'Cindy Leschaud',
        'email'      => 'cindy.leschaud@unine.ch',
        'password'   => bcrypt('cindy2')
    ];
});*/

$factory->define(App\Droit\Newsletter\Entities\Newsletter_lists::class, function (Faker\Generator $faker) {
    return [
        'title' => 'Sujet'
    ];
});

$factory->define(App\Droit\Newsletter\Entities\Newsletter_emails::class, function (Faker\Generator $faker) {
    return [
        'email'=> $faker->email
    ];
});

$factory->define(App\Droit\Shop\Stock\Entities\Stock::class, function (Faker\Generator $faker) {
    return [
        'product_id' => 1,
        'amount' => '1',
        'motif' => 'motif du changement',
        'operator' => '+',
        'created_at' => date('Y-m-d')
    ];
});


$factory->define(App\Droit\Newsletter\Entities\Newsletter_contents::class, function (Faker\Generator $faker) {
    return [
        'type_id'       => 6, // text
        'titre'        => null,
        'contenu'      => null,
        'image'        => null,
        'lien'         => null,
        'arret_id'     => null,
        'categorie_id' => null,
        'product_id'   => null,
        'colloque_id'  => null,
        'newsletter_campagne_id' => 1,
        'rang'      => 1,
        'groupe_id' => null,
    ];
});
