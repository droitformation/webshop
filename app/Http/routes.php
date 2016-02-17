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
Route::post('check/email', 'AjaxController@check');

Route::group(['prefix' => 'bail'], function () {

    Route::get('/', array('uses' => 'BailController@index'));
    Route::get('page/{slug}/{var?}', array('uses' => 'BailController@page'));
    Route::get('lois', array('uses' => 'BailController@lois'));
    Route::get('jurisprudence', array('uses' => 'BailController@jurisprudence'));
    Route::get('doctrine', array('uses' => 'BailController@doctrine'));
    Route::get('calcul', array('uses' => 'BailController@calcul'));
    Route::post('loyer', array('uses' => 'BailController@loyer'));

});

Route::group(['prefix' => 'matrimonial'], function () {

    Route::get('/', array('uses' => 'MatrimonialController@index'));
    Route::get('page/{slug}/{var?}', array('uses' => 'MatrimonialController@page'));
    Route::get('jurisprudence', array('uses' => 'MatrimonialController@jurisprudence'));
    Route::get('newsletter/{id?}', array('uses' => 'MatrimonialController@newsletter'));
});

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

    Route::get('user/search', 'Backend\User\UserController@search');
    Route::get('config/shop', 'Backend\ConfigController@shop');
    Route::get('config/abo', 'Backend\ConfigController@abo');
    Route::get('config/colloque', 'Backend\ConfigController@colloque');
    Route::resource('config', 'Backend\ConfigController');

    Route::get('reminder/create/{type}','Backend\ReminderController@create');
    Route::resource('reminder', 'Backend\ReminderController');

    Route::get('search/form', 'Backend\SearchController@form');
    Route::post('search/user', 'Backend\SearchController@user');
    Route::get('search/adresse', 'Backend\SearchController@adresse');

    Route::get('search', 'Backend\SearchController@search');

    Route::resource('faq', 'Backend\FaqController');
    Route::get('question/create/{categorie}', 'Backend\QuestionController@create');
    Route::resource('question', 'Backend\QuestionController');

    /*
    |--------------------------------------------------------------------------
    | User and Adresse Backend Routes
    |--------------------------------------------------------------------------
    */

    Route::post('adresse/convert','Backend\User\AdresseController@convert');
    Route::resource('adresse', 'Backend\User\AdresseController');
    Route::get('users', 'Backend\User\UserController@users');
    Route::resource('user', 'Backend\User\UserController');

    Route::resource('duplicate', 'Backend\User\DuplicateController');
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
    | Ajax members Routes
    |--------------------------------------------------------------------------
    */

    Route::get('member/search', 'Backend\MemberController@search');
    Route::delete('member/destroy', 'Backend\MemberController@destroy');
    Route::resource('member', 'Backend\MemberController');

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
    Route::post('inscription/edit', 'Backend\Colloque\InscriptionController@edit');
    Route::post('inscription/push', 'Backend\Colloque\InscriptionController@push');
    Route::post('inscription/change', 'Backend\Colloque\InscriptionController@change');
    Route::post('inscription/send', 'Backend\Colloque\InscriptionController@send');
    Route::resource('inscription', 'Backend\Colloque\InscriptionController');

    Route::resource('colloque', 'Backend\Colloque\ColloqueController');
    Route::get('colloque/location/{id}', 'Backend\Colloque\ColloqueController@location');
    Route::get('colloque/adresse/{id}', 'Backend\Colloque\ColloqueController@adresse');
    Route::get('colloque/generate/{id}/{doc}', 'Backend\Colloque\ColloqueController@generate');

    Route::post('colloque/addprice', 'Backend\Colloque\ColloqueController@addprice');
    Route::post('colloque/removeprice', 'Backend\Colloque\ColloqueController@removeprice');
    Route::post('colloque/editprice', 'Backend\Colloque\ColloqueController@editprice');

    Route::post('colloque/addoption', 'Backend\Colloque\ColloqueController@addoption');
    Route::post('colloque/removeoption', 'Backend\Colloque\ColloqueController@removeoption');
    Route::post('colloque/editoption', 'Backend\Colloque\ColloqueController@editoption');

    Route::resource('document', 'Backend\Colloque\DocumentController');

    Route::post('export/inscription', 'Backend\ExportController@inscription');
    Route::get('export/view', 'Backend\ExportController@view');
    Route::get('export/generate', 'Backend\ExportController@generate');
    Route::match(['get', 'post'],'export/search', 'Backend\ExportController@search');

    Route::get('download/{file}', function($file)
    {
        $file = storage_path('excel/exports/'.$file);

        return Response::download($file);
    });

    /*
    |--------------------------------------------------------------------------
    | Inscriptions and colloque Routes
    |--------------------------------------------------------------------------
    */

    Route::resource('site', 'Backend\SiteController');
    Route::resource('coupon', 'Backend\Shop\CouponController');
    Route::resource('shipping', 'Backend\Shop\ShippingController');
    Route::resource('theme', 'Backend\Shop\ThemeController');
    Route::resource('domain', 'Backend\Shop\DomainController');
    Route::resource('attribut', 'Backend\Shop\AttributController');

    Route::post('product/addAttribut/{id}', 'Backend\Shop\ProductController@addAttribut');
    Route::post('product/removeAttribut/{id}', 'Backend\Shop\ProductController@removeAttribut');
    Route::post('product/addType/{id}', 'Backend\Shop\ProductController@addType');
    Route::post('product/removeType/{id}', 'Backend\Shop\ProductController@removeType');
    Route::match(['get', 'post'], 'products', 'Backend\Shop\ProductController@index');
    Route::resource('product', 'Backend\Shop\ProductController');

    Route::match(['get', 'post'],'orders', 'Backend\Shop\OrderController@index');
    Route::post('order/edit', 'Backend\Shop\OrderController@edit');
    Route::post('order/export', 'Backend\Shop\OrderController@export');
    Route::post('order/restore/{id}', 'Backend\Shop\OrderController@restore');
    Route::post('order/generate', 'Backend\Shop\OrderController@generate');
    Route::resource('order', 'Backend\Shop\OrderController');

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

    Route::post('files', ['uses' => 'Backend\FileController@files']);
    Route::get('tree', ['uses' => 'Backend\FileController@tree']);
    Route::post('files/delete', ['uses' => 'Backend\FileController@delete']);
    Route::post('files/crop', ['uses' => 'Backend\FileController@crop']);

    /*
    |--------------------------------------------------------------------------
    | Abonnements Routes
    |--------------------------------------------------------------------------
    */

    Route::match(['get', 'post'], 'abonnements/{id?}', 'Backend\Abo\AboUserController@index');
    Route::get('abonnement/create/{id}', 'Backend\Abo\AboUserController@create');
    Route::post('abonnement/export', 'Backend\Abo\AboController@export');
    Route::post('abonnement/restore/{id}', 'Backend\Abo\AboUserController@restore');
    Route::resource('abonnement', 'Backend\Abo\AboUserController');
    Route::resource('rappel', 'Backend\Abo\AboFactureController');
    Route::match(['get', 'post'],'factures/{id}', 'Backend\Abo\AboFactureController@index');

    Route::get('facture/generate/{id}', 'Backend\Abo\AboFactureController@generate');

    Route::resource('facture', 'Backend\Abo\AboFactureController');
    Route::resource('abo', 'Backend\Abo\AboController');

    /*
    |--------------------------------------------------------------------------
    | Content, arrets and analyses Routes
    |--------------------------------------------------------------------------
    */

    Route::get('arrets/{site}','Backend\Content\ArretController@index');
    Route::get('arret/create/{site}','Backend\Content\ArretController@create');
    Route::resource('arret',     'Backend\Content\ArretController');

    Route::get('analyses/{site}','Backend\Content\AnalyseController@index');
    Route::get('analyse/create/{site}','Backend\Content\AnalyseController@create');
    Route::resource('analyse',   'Backend\Content\AnalyseController');

    Route::get('categories/{site}','Backend\Content\CategorieController@index');
    Route::get('categorie/create/{site}','Backend\Content\CategorieController@create');
    Route::resource('categorie', 'Backend\Content\CategorieController');

    Route::get('blocs/{site}','Backend\BlocController@index');
    Route::get('bloc/create/{site}','Backend\BlocController@create');
    Route::resource('bloc',      'Backend\BlocController');

    Route::get('menus/{site}','Backend\MenuController@index');
    Route::get('menu/create/{site}','Backend\MenuController@create');
    Route::resource('menu', 'Backend\MenuController');

    Route::resource('author',    'Backend\Content\AuthorController');

    Route::get('pages/{site}','Backend\PageController@index');
    Route::get('page/create/{site}','Backend\PageController@create');
    Route::post('page/sorting','Backend\PageController@sorting');
    Route::resource('page',      'Backend\PageController');

    Route::get('pagecontent/{type}/{page}','Backend\PageContentController@index');
    Route::resource('pagecontent', 'Backend\PageContentController');

    /*
  |--------------------------------------------------------------------------
  | Backend subscriptions, newsletters and campagnes Routes
  |--------------------------------------------------------------------------
  */

    Route::post('sorting', 'Backend\Newsletter\CampagneController@sorting');
    Route::post('sortingGroup', 'Backend\Newsletter\CampagneController@sortingGroup');

    Route::get('ajax/arret/{id}',   'Backend\Content\ArretController@simple');
    Route::get('ajax/arrets/{id?}', 'Backend\Content\ArretController@arrets');
    Route::get('ajax/analyses/{id}','Backend\Content\AnalyseController@simple');
    Route::get('ajax/categories',   'Backend\Content\CategorieController@categories');
    Route::post('ajax/categorie/arrets','Backend\Content\CategorieController@arrets');

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

    Route::resource('import', 'Backend\Newsletter\ImportController');
    Route::resource('statistics', 'Backend\Newsletter\StatsController');

});

/* *
 * Shop routes for frontend shop
 * */
Route::get('/', 'Frontend\Shop\ShopController@index');
Route::match(['get', 'post'], 'products', 'Frontend\Shop\ShopController@products');
Route::get('product/{id}', 'Frontend\Shop\ShopController@show');

Route::match(['get', 'post'], 'sort', 'Frontend\Shop\ShopController@sort');

Route::group(['middleware' => ['auth','pending','cart']], function () {

    /* *
     * Checkout routes for frontend shop
     * */
    Route::get('checkout/cart',  'Frontend\Shop\CheckoutController@cart');
    Route::get('checkout/billing',  'Frontend\Shop\CheckoutController@billing');
    Route::match(['get', 'post'],'checkout/resume', 'Frontend\Shop\CheckoutController@resume');
    Route::get('checkout/confirm',  'Frontend\Shop\CheckoutController@confirm');
    Route::match(['get', 'post'],'checkout/send', 'Frontend\Shop\CheckoutController@send');
});

/* *
 * Cart routes for frontend shop
 * */
Route::post('cart/addProduct', 'Frontend\Shop\CartController@addProduct');
Route::post('cart/removeProduct', 'Frontend\Shop\CartController@removeProduct');
Route::post('cart/quantityProduct', 'Frontend\Shop\CartController@quantityProduct');
Route::post('cart/applyCoupon', 'Frontend\Shop\CartController@applyCoupon');

//Route::get('home', 'HomeController@index');

/* *
 * Update user adresse via ajax
 * */
Route::resource('adresse', 'Frontend\User\AdresseController');
Route::post('ajax/adresse/{id}', 'Frontend\User\AdresseController@ajaxUpdate');

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::get('auth/admin', 'Auth\AuthController@getAdmin');
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
Route::get('login', 'Auth\AuthController@login');

Route::get('auth/droithub', 'Auth\AuthController@redirectToProvider');

//Route::get('auth/droithub/callback', 'Auth\AuthController@handleProviderCallback');

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

    return redirect($redirectUri);

}]);

Route::post('oauth/access_token', function() {
    return Response::json(Authorizer::issueAccessToken());
});

Route::get('api/user', ['middleware' => 'oauth', function(){

    $user_id = Authorizer::getResourceOwnerId();
    $user    = \App\Droit\User\Entities\User::find($user_id);

    return Response::json(['first_name' => $user->first_name, 'last_name' => $user->last_name, 'email' => $user->email, 'id' => $user_id]);

}]);

/*
 * Only for development
 * */
if (App::environment('local'))
{
    require app_path().'/Http/dev.php';
}