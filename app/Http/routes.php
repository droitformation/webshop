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
Route::resource('colloque', 'ColloqueController');
Route::get('checkout/resume', 'CheckoutController@resume');
Route::get('checkout/confirm', 'CheckoutController@confirm');
Route::match(['get', 'post'],'checkout/send', 'CheckoutController@send');

Route::resource('adresse', 'AdresseController');
Route::post('ajax/adresse/{id}', 'AdresseController@ajaxUpdate');

Route::get('profil', 'ProfileController@index');
Route::get('profil/orders', 'ProfileController@orders');
Route::get('profil/colloques', 'ProfileController@colloques');

Route::post('addProduct', 'CartController@addProduct');
Route::post('removeProduct', 'CartController@removeProduct');
Route::post('quantityProduct', 'CartController@quantityProduct');
Route::post('applyCoupon', 'CartController@applyCoupon');

//Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

/*
 * Payement via Paypal
 * */


/*
 * Test routes
 * */

Route::get('cartworker', function()
{
    $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorker');
    $coupon = \App::make('App\Droit\Shop\Coupon\Repo\CouponInterface');
    $order  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
    $user   = \App::make('App\Droit\User\Repo\UserInterface');

    $pdf    = new App\Droit\Generate\Pdf\PdfGenerator( $order,$user );

    $order_no = $order->find(21);
    $create = new App\Jobs\CreateOrderInvoice($order_no);

    //$order_no = $order->find(6);
    //return $pdf->factureOrder(6,true);

    echo '<pre>';
    print_r($create->handle());
    echo '</pre>';

    //event(new App\Events\OrderWasPlaced($order_no));

});

Route::get('notification', function()
{
    setlocale(LC_ALL, 'fr_FR.UTF-8');
    $date   = \Carbon\Carbon::now()->formatLocalized('%d %B %Y');
    $title  = 'Votre commande sur publications-droit.ch';
    $logo   = 'facdroit.png';
    $orders  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');

    $order = $orders->find(8);
    $order->load('products','user','shipping','payement');

    $duDate = $order->created_at->addDays(30)->formatLocalized('%d %B %Y');

    $products = $order->products->groupBy('id');

    $data = [
        'title'     => $title,
        'logo'      => $logo,
        'order'     => $order,
        'products'  => $products,
        'date'      => $date,
        'duDate'    => $duDate
    ];

    return View::make('emails.shop.confirmation', $data);

});


Route::get('factory', function()
{

    $fakerobj = new Faker\Factory;
    $faker = $fakerobj::create();

    $repo = \App::make('App\Droit\Shop\Product\Repo\ProductInterface');

    for( $x = 1 ; $x < 11; $x++ )
    {
        $product = $repo->create(array(
            'title'           => $faker->sentence,
            'teaser'          => $faker->paragraph,
            'description'     => $faker->text,
            'image'           => 'img'.$x.'.jpg',
            'price'           => $faker->numberBetween(2000, 40000),
            'weight'          => $faker->numberBetween(200, 1000),
            'sku'             => $faker->numberBetween(5, 50),
            'is_downloadable' => (($x % 2) == 0 ? 1 : 0)
        ));
        
        echo '<pre>';
        print_r($product);
        echo '</pre>';
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

Event::listen('illuminate.query', function($query, $bindings, $time, $name)
{
    $data = compact('bindings', 'time', 'name');

    // Format binding data for sql insertion
    foreach ($bindings as $i => $binding)
    {
        if ($binding instanceof \DateTime)
        {
            $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
        }
        else if (is_string($binding))
        {
            $bindings[$i] = "'$binding'";
        }
    }

    // Insert bindings into query
    $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
    $query = vsprintf($query, $bindings);

    Log::info($query, $data);
});

