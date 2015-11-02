<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

//Route::resource('product', 'ProductController');
Route::get('colloque', 'Frontend\Colloque\ColloqueController@index');
Route::get('colloque/{id}', 'Frontend\Colloque\ColloqueController@show');
Route::get('code', 'CodeController@index');

/*
|--------------------------------------------------------------------------
| Subscriptions adn newsletter Routes
|--------------------------------------------------------------------------
*/
Route::get('campagne/{id}', 'Frontend\CampagneController@show');

/* Routes to implement  */
/*
    Route::get('newsletter', 'Frontend\NewsletterController@index');
    Route::resource('newsletter', 'Frontend\NewsletterController');
    Route::get('newsletter/campagne/{id}', 'Frontend\NewsletterController@campagne');
*/
    Route::post('unsubscribe', 'Backend\Newsletter\InscriptionController@unsubscribe');
    Route::post('subscribe', 'Backend\Newsletter\InscriptionController@subscribe');
    Route::get('activation/{token}', 'Backend\Newsletter\InscriptionController@activation');


Route::group(['middleware' => 'auth'], function () {

    /* *
     * Inscriptions pages
     * */
    Route::get('colloque/inscription/{id}', ['middleware' => ['registered','pending'], 'uses' => 'Frontend\Colloque\ColloqueController@inscription']);
    Route::post('registration', ['uses' => 'Frontend\Colloque\InscriptionController@store']);

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

    Route::get('/', 'Backend\AdminController@index');
    Route::get('config/shop', 'Backend\ConfigController@shop');
    Route::get('config/colloque', 'Backend\ConfigController@colloque');
    Route::resource('config', 'Backend\ConfigController');

    Route::get('search', 'Backend\SearchController@search');

    /*
    |--------------------------------------------------------------------------
    | User and Adresse Backend Routes
    |--------------------------------------------------------------------------
    */

    Route::resource('adresse', 'Backend\User\AdresseController');
    Route::get('users', 'Backend\User\UserController@users');
    Route::resource('user', 'Backend\User\UserController');

    /*
    |--------------------------------------------------------------------------
    | Ajax specialisations Routes
    |--------------------------------------------------------------------------
    */

    Route::get('specialisation/search', 'Backend\SpecialisationController@search');
    Route::delete('specialisation/destroy', 'Backend\SpecialisationController@destroy');
    Route::resource('specialisation', 'Backend\SpecialisationController');

    /*
    |--------------------------------------------------------------------------
    | Inscriptions and colloque Routes
    |--------------------------------------------------------------------------
    */

    Route::get('inscription/colloque/{id}', 'Backend\Colloque\InscriptionController@colloque');
    Route::get('inscription/create/{id?}', 'Backend\Colloque\InscriptionController@create');
    Route::get('inscription/add/{group_id}', 'Backend\Colloque\InscriptionController@add');
    Route::get('inscription/generate/{id}', 'Backend\Colloque\InscriptionController@generate');
    Route::post('inscription/restore/{id}', 'Backend\Colloque\InscriptionController@restore');
    Route::get('inscription/groupe/{id}', 'Backend\Colloque\InscriptionController@groupe');
    Route::post('inscription/type', 'Backend\Colloque\InscriptionController@inscription');
    Route::post('inscription/push', 'Backend\Colloque\InscriptionController@push');
    Route::post('inscription/change', 'Backend\Colloque\InscriptionController@change');
    Route::resource('inscription', 'Backend\Colloque\InscriptionController');

    Route::resource('colloque', 'Backend\Colloque\ColloqueController');
    Route::get('colloque/location/{id}', 'Backend\Colloque\ColloqueController@location');
    Route::get('colloque/adresse/{id}', 'Backend\Colloque\ColloqueController@adresse');
    Route::get('colloque/generate/{id}/{doc}', 'Backend\Colloque\ColloqueController@generate');
    Route::post('colloque/addprice', 'Backend\Colloque\ColloqueController@addprice');
    Route::post('colloque/removeprice', 'Backend\Colloque\ColloqueController@removeprice');
    Route::post('colloque/addoption', 'Backend\Colloque\ColloqueController@addoption');
    Route::post('colloque/removeoption', 'Backend\Colloque\ColloqueController@removeoption');

    Route::resource('document', 'Backend\Colloque\DocumentController');

    Route::get('export/inscription/{id}', 'Backend\ExportController@inscription');

    /*
    |--------------------------------------------------------------------------
    | Inscriptions and colloque Routes
    |--------------------------------------------------------------------------
    */

    Route::resource('coupon', 'Backend\CouponController');
    Route::resource('shipping', 'Backend\ShippingController');

    /*
    |--------------------------------------------------------------------------
    | Upload files direct, with jquery and redactor Routes
    |--------------------------------------------------------------------------
    */

    Route::post('upload', 'Backend\UploadController@upload');
    Route::post('uploadFile', 'Backend\UploadController@uploadFile');
    Route::post('uploadJS', 'Backend\UploadController@uploadJS');
    Route::post('uploadRedactor', 'Backend\UploadController@uploadRedactor');

    Route::get('imageJson/{id?}', ['uses' => 'Backend\UploadController@imageJson']);
    Route::get('fileJson/{id?}', ['uses' => 'Backend\UploadController@fileJson']);

    /*
    |--------------------------------------------------------------------------
    | Content, arrets and analyses Routes
    |--------------------------------------------------------------------------
    */

    Route::resource('arret',     'Backend\Content\ArretController');
    Route::resource('analyse',   'Backend\Content\AnalyseController');
    Route::resource('categorie', 'Backend\Content\CategorieController');
    Route::resource('contenu',   'Backend\Content\ContentController');
    Route::resource('author',    'Backend\Content\AuthorController');

    /*
  |--------------------------------------------------------------------------
  | Backend subscriptions, newsletters and campagnes Routes
  |--------------------------------------------------------------------------
  */

    Route::post('sorting', 'Backend\Newsletter\CampagneController@sorting');
    Route::post('sortingGroup', 'Backend\Newsletter\CampagneController@sortingGroup');

    Route::get('ajax/arrets/{id}',   'Backend\Content\ArretController@simple');
    Route::get('ajax/arrets',        'Backend\Content\ArretController@arrets');
    Route::get('ajax/analyses/{id}', 'Backend\Content\AnalyseController@simple');
    Route::get('ajax/categories',    'Backend\Content\CategorieController@categories');
    Route::post('ajax/categorie/arrets', 'Backend\Content\CategorieController@arrets');

    Route::resource('newsletter', 'Backend\Newsletter\NewsletterController');

    Route::post('campagne/send', 'Backend\Newsletter\CampagneController@send');
    Route::post('campagne/test', 'Backend\Newsletter\CampagneController@test');
    Route::post('campagne/sorting', 'Backend\Newsletter\CampagneController@sorting');
    Route::post('campagne/process', 'Backend\Newsletter\CampagneController@process');
    Route::post('campagne/editContent', 'Backend\Newsletter\CampagneController@editContent');
    Route::post('campagne/remove', 'Backend\Newsletter\CampagneController@remove');
    Route::get('campagne/create/{newsletter}', 'Backend\Newsletter\CampagneController@create');
    Route::get('campagne/simple/{id}', 'Backend\Newsletter\CampagneController@simple');
    Route::resource('campagne', 'Backend\Newsletter\CampagneController');

    Route::resource('subscriber', 'Backend\Newsletter\SubscriberController');
    Route::get('subscribers', ['uses' => 'Backend\Newsletter\SubscriberController@subscribers']);

    Route::resource('statistics', 'Backend\Newsletter\StatsController');

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

/* *
 * Update user adresse via ajax
 * */
Route::resource('adresse', 'Frontend\User\AdresseController');
Route::post('ajax/adresse/{id}', 'Frontend\User\AdresseController@ajaxUpdate');

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

    $coupon = \App::make('App\Droit\Shop\Coupon\Repo\CouponInterface');
    $item   = $coupon->find(1);
    $item->load('orders');

    echo '<pre>';
    print_r($item);
    echo '</pre>';

    /*  
       $worker       = \App::make('App\Droit\Shop\Cart\Worker\CartWorker');

       $order        = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
       $user         = \App::make('App\Droit\User\Repo\UserInterface');
       $inscription  = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
       $colloque    = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
       $generator    = new \App\Droit\Generate\Pdf\PdfGenerator();
   
     $gro = new \App\Droit\Inscription\Entities\Groupe();
       $groupe = $gro::findOrNew(25);
       $groupe->load('colloque','user','inscriptions');
   
       $user = $groupe->user;
       $user->load('adresses');
       $groupe->setAttribute('adresse_facturation',$user->adresse_facturation);

    $inde  = $colloque->find(71);
    $excel = new App\Droit\Generate\Excel\ExcelGenerator();

    //$inde->load('inscriptions');
    $cindy = $inscription->hasPayed(1);

    $columns = [
        'civilite_title' ,'name', 'email', 'company', 'profession_title', 'telephone','mobile',
        'fax', 'adresse', 'cp', 'complement','npa', 'ville', 'canton_title','pays_title'
    ];


    $adresse    = \App::make('App\Droit\Adresse\Repo\AdresseInterface');
    $me = $adresse->find(1);
    $me->load('typeadresse');
    echo '<pre>';
    print_r($me);
    echo '</pre>';
    */
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

