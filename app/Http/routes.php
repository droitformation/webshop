<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

//Route::resource('product', 'ProductController');
Route::get('colloque', 'Frontend\Colloque\ColloqueController@index');
Route::get('colloque/{id}', 'Frontend\Colloque\ColloqueController@show');

Route::group(['middleware' => 'auth'], function () {

    /* *
     * Inscriptions pages
     * */
    Route::get('colloque/inscription/{id}', 'Frontend\Colloque\ColloqueController@inscription');
    Route::post('registration', 'Frontend\Colloque\InscriptionController@registration');

    /* *
     * User profile routes
     * */
    Route::get('profil', 'ProfileController@index');
    Route::match(['put', 'post'],'profil/update', 'ProfileController@update');
    Route::get('profil/orders', 'ProfileController@orders');
    Route::get('profil/colloques', 'ProfileController@colloques');
    Route::get('profil/inscription/{id}', 'ProfileController@inscription');

});

Route::group(['prefix' => 'admin', 'middleware' => ['auth','administration']], function () {

    Route::get('/', 'Admin\AdminController@index');
    Route::get('inscription/colloque/{id}', 'Admin\Colloque\InscriptionController@colloque');
    Route::get('inscription/create/{id?}', 'Admin\Colloque\InscriptionController@create');
    Route::get('inscription/add/{group_id}', 'Admin\Colloque\InscriptionController@add');
    Route::get('inscription/generate/{id}', 'Admin\Colloque\InscriptionController@generate');
    Route::post('inscription/restore/{id}', 'Admin\Colloque\InscriptionController@restore');
    Route::get('inscription/groupe/{id}', 'Admin\Colloque\InscriptionController@groupe');
    Route::post('inscription/type', 'Admin\Colloque\InscriptionController@inscription');
    Route::post('inscription/push', 'Admin\Colloque\InscriptionController@push');
    Route::post('inscription/change', 'Admin\Colloque\InscriptionController@change');
    Route::resource('inscription', 'Admin\Colloque\InscriptionController');
    Route::resource('colloque', 'Admin\Colloque\ColloqueController');
    Route::get('search', 'Admin\SearchController@search');
    Route::get('export/inscription/{id}', 'Admin\ExportController@inscription');
});

/* *
 * Shop routes for frontend shop
 * */
Route::get('/', 'Frontend\Shop\ShopController@index');
Route::get('shop', 'Frontend\Shop\ShopController@index');
Route::get('shop/product/{id}', 'Frontend\Shop\ShopController@show');

/* *
 * Checkout routes for frontend shop
 * */
Route::get('checkout/resume', 'Frontend\Shop\CheckoutController@resume');
Route::get('checkout/confirm', 'Frontend\Shop\CheckoutController@confirm');
Route::match(['get', 'post'],'checkout/send', 'Frontend\Shop\CheckoutController@send');

Route::resource('adresse', 'AdresseController');
Route::post('ajax/adresse/{id}', 'AdresseController@ajaxUpdate');

/* *
 * Cart routes for frontend shop
 * */
Route::post('cart/addProduct', 'Frontend\Shop\CartController@addProduct');
Route::post('cart/removeProduct', 'Frontend\Shop\CartController@removeProduct');
Route::post('cart/quantityProduct', 'Frontend\Shop\CartController@quantityProduct');
Route::post('cart/applyCoupon', 'Frontend\Shop\CartController@applyCoupon');

//Route::get('home', 'HomeController@index');

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');
// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');
Route::get('password/new', 'Auth\PasswordController@getNew');
Route::post('password/define', 'Auth\PasswordController@postDefine');
Route::get('login/{provider?}', 'Auth\AuthController@login');

/* *
 * Oauth 2 routes
 * */
Route::get('oauth/authorize', ['as' => 'oauth.authorize.get','middleware' => ['check-authorization-params', 'auth'], function() {
    // display a form where the user can authorize the client to access it's data
    $authParams = Authorizer::getAuthCodeRequestParams();

    $formParams = array_except($authParams,'client');
    $formParams['client_id'] = $authParams['client']->getId();
    return view('oauth.authorization-form', ['params'=>$formParams,'client'=>$authParams['client']]);
}]);

Route::post('oauth/authorize', ['as' => 'oauth.authorize.post','middleware' => ['check-authorization-params', 'auth'], function() {

    $params = Authorizer::getAuthCodeRequestParams();
    $params['user_id'] = Auth::user()->id;

    $redirectUri = '';

    // if the user has allowed the client to access its data, redirect back to the client with an auth code
    if (Input::get('approve') !== null) {
        $redirectUri = Authorizer::issueAuthCode('user', $params['user_id'], $params);
    }

    // if the user has denied the client to access its data, redirect back to the client with an error message
    if (Input::get('deny') !== null) {
        $redirectUri = Authorizer::authCodeRequestDeniedRedirectUri();
    }

    return Redirect::to($redirectUri);

}]);

Route::post('oauth/access_token', function() {
    return Response::json(Authorizer::issueAccessToken());
});

Route::get('api/user', ['middleware' => 'oauth', function(){

    $user_id = Authorizer::getResourceOwnerId();
    $user    = \App\Droit\User\Entities\User::find($user_id);

    return Response::json(['first_name' => $user->first_name, 'last_name' => $user->last_name, 'email' => $user->email, 'id' => $user_id]);

}]);


/* ============================================
 * Test routes
 ============================================ */

Route::get('cartworker', function()
{
    $worker       = \App::make('App\Droit\Shop\Cart\Worker\CartWorker');
    $coupon       = \App::make('App\Droit\Shop\Coupon\Repo\CouponInterface');
    $order        = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
    $user         = \App::make('App\Droit\User\Repo\UserInterface');
    $inscription  = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');

    $generator    = new \App\Droit\Generate\Pdf\PdfGenerator();

    $gro = new \App\Droit\Inscription\Entities\Groupe();
    $groupe = $gro::findOrNew(25);
    $groupe->load('colloque','user','inscriptions');

/*    echo '<pre>';
    print_r($groupe->documents);
    echo '</pre>';exit;*/

    $user = $groupe->user;
    $user->load('adresses');
    $groupe->setAttribute('adresse_facturation',$user->adresse_facturation);

/*    $inscription  = new \App\Droit\Inscription\Entities\Inscription();
    $restore = $inscription->withTrashed()->find(74)->restore();
    $inscription->withTrashed()->find(74)->participant()->restore();
    $inscription->withTrashed()->find(74)->user_options()->restore();*/

    $generator->stream = true;

    //$job = (new \App\Jobs\MakeDocumentGroupe($groupe));

    //$job->handle();

    echo '<pre>';
    print_r($groupe->adresse_facturation->name);
    echo '</pre>';exit;

    //$order_no = $order->find(21);
    //$create = new App\Jobs\CreateOrderInvoice($order_no);

    //$order_no = $order->find(6);
    //print_r($create->handle());
    //return $generator->factureOrder(34,true);

    //return $pdf->bvEvent($inscrit,true);

    //event(new App\Events\InscriptionWasRegistered($inscrit));

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
        'concerne'  => 'Commande',
        'order'     => $order,
        'products'  => $products,
        'date'      => $date,
        'duDate'    => $duDate
    ];

    return View::make('emails.shop.confirmation', $data);

});

Route::get('registration', function()
{
    setlocale(LC_ALL, 'fr_FR.UTF-8');

    $date   = \Carbon\Carbon::now()->formatLocalized('%d %B %Y');
    $title  = 'Votre inscription sur publications-droit.ch';
    $logo   = 'facdroit.png';

    $inscription = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
    $inscrit     = $inscription->find(1);

    $data = [
        'title'       => $title,
        'concerne'    => 'Inscription',
        'logo'        => $logo,
        'inscription' => $inscrit,
        'annexes'     => $inscrit->colloque->annexe,
        'date'        => $date,
    ];

    return View::make('emails.colloque.confirmation', $data);

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

Route::get('otherfactory', function()
{

    $path = public_path('files/colloques/');
    $documents = [];
    $temp      = new \App\Droit\Colloque\Entities\Colloque();
    $colloques = $temp->all();

    foreach($colloques as $colloque){

       foreach($colloque->documents as $document)
       {

           $file = $path.'temp/'.$document->path;

           //if (File::exists($file))
          // {
               if (!File::copy($file, $path.$document->type.'/'.$document->path))
               {
                   die("Couldn't copy file");
               }

               $documents[] = $path.$document->path;
           //}
       }
    }

    echo '<pre>';
    print_r($documents);
    echo '</pre>';exit;

});


Route::get('convert', function()
{
    $temp      = new \App\Droit\Colloque\Entities\Colloque_temp();
    $colloques = $temp->all();

    foreach($colloques as $colloque)
    {


        $new  = new \App\Droit\Colloque\Entities\Colloque();
        $item = $new->find($colloque->id);

        $item->organisateur = $colloque->organisateur_id;
        $item->compte_id    = $colloque->compte_id;
        $item->save();

/*        $new->id              = $colloque->id;
        $new->titre           = $colloque->titre;
        $new->soustitre       = $colloque->soustitre;
        $new->sujet           = $colloque->sujet;
        $new->start_at        = $colloque->start_at;
        $new->end_at          = $colloque->end_at;
        $new->active_at       = $colloque->active_at;
        $new->registration_at = $colloque->registration_at;
        $new->remarques       = $colloque->remarques;
        $new->visible         = $colloque->visible;
        $new->url             = $colloque->url;
        $new->compte_id       = $colloque->compte_id;

        if($colloque->typeColloque == 1){
            $new->bon = 1;
            $new->facture = 1;
        }

        if($colloque->typeColloque == 2){
            $new->bon = 0;
            $new->facture = 1;
        }

        if($colloque->typeColloque == 0){
            $new->bon = 0;
            $new->facture = 0;
        }*/

        //$new->save();
    }

});

Route::get('prix', function()
{
    $price  = new App\Droit\Price\Entities\Prix;
    $prices = $price->all();

    foreach($prices as $prix)
    {
        $new  = new \App\Droit\Price\Entities\Price();

        $new->id            = $prix->id;
        $new->colloque_id   = $prix->colloque_id;
        $new->price         = $prix->price * 100;
        $new->description   = $prix->description;
        $new->rang          = $prix->rang;

        if($prix->type == 1)
        {
            $new->type = 'public';
        }

        if($prix->type == 2)
        {
            $new->type = 'admin';
        }

        //$new->save();
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

