<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'ProductController@index');
Route::resource('product', 'ProductController');

Route::get('checkout/resume', 'CheckoutController@resume');
Route::get('checkout/confirm', 'CheckoutController@confirm');

Route::post('addProduct', 'CartController@addProduct');
Route::post('removeProduct', 'CartController@removeProduct');
Route::post('quantityProduct', 'CartController@quantityProduct');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

/*
 * Test routes
 * */

Route::get('factory', function()
{

    $fakerobj = new Faker\Factory;
    $faker = $fakerobj::create();
    $date = \Carbon\Carbon::now();

    $fillable = array('title', 'teaser', 'image', 'description', 'weight', 'sku', 'is_downloadable' ,'hidden');

    for( $x = 1 ; $x < 11; $x++ )
    {
        App\Droit\Shop\Product\Entities\Product::create(array(
            'title'           => $faker->sentence,
            'teaser'          => $faker->paragraph,
            'description'     => $faker->text,
            'image'           => 'img'.$x.'.jpg',
            'price'           => $faker->randomFloat(2,20,200),
            'weight'          => $faker->numberBetween(200, 1000),
            'sku'             => $faker->numberBetween(5, 50),
            'is_downloadable' => (($x % 2) == 0 ? 1 : 0)
        ));
    }

});

Route::get('myaddress', function()
{
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
        'user_id'       => 1,
        'livraison'     => 1,
    ));
    
    echo '<pre>';
    print_r($adresse);
    echo '</pre>';

});
