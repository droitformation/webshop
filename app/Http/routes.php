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

Route::post('addProduct', 'CartController@addProduct');
Route::post('removeProduct', 'CartController@removeProduct');

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
