<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('code', 'CodeController@index');

/*
|--------------------------------------------------------------------------
| Validate presence for inscriptions
|--------------------------------------------------------------------------
*/
Route::get('presence/{id}/{key}', 'CodeController@presence');
Route::get('presence/occurrence/{id}/{key}', 'CodeController@occurrence');

/*
|--------------------------------------------------------------------------
| Ajax login validation
|--------------------------------------------------------------------------
*/
Route::post('check/email', 'Api\ValidationController@check');
Route::post('check/name', 'Api\ValidationController@name');

/*
|--------------------------------------------------------------------------
| Sondages routes
|--------------------------------------------------------------------------
*/
Route::get('reponse/create/{token}', 'ReponseController@create');

/*
|--------------------------------------------------------------------------
| Send message form contact
|--------------------------------------------------------------------------
*/

Route::post('sendMessage','ContactController@sendMessage');

Route::group(['middleware' => 'site'], function () {

    // For now...
    Route::get('/', function () {
        return redirect('pubdroit');
    });

    Route::group(['prefix' => 'pubdroit'], function () {

        Route::get('page/{slug}/{var?}', array('uses' => 'Frontend\Shop\ShopController@page'));
        /* *
          * Colloques
          * */
        Route::get('colloque', 'Frontend\Colloque\ColloqueController@index');
        Route::get('colloque/{id}', 'Frontend\Colloque\ColloqueController@show');

        Route::group(['middleware' => 'auth'], function () {

            /* *
             * Inscriptions pages
             * */
            Route::get('colloque/inscription/{id}', ['middleware' => ['registered','pending'], 'uses' => 'Frontend\Colloque\ColloqueController@inscription']);
            Route::post('registration', ['uses' => 'Frontend\Colloque\InscriptionController@store']);

            /* *
             * User profile routes
             * */
            Route::get('profil', 'Frontend\ProfileController@index');
            Route::match(['put', 'post'],'profil/update', 'Frontend\ProfileController@update');
            Route::get('profil/orders', 'Frontend\ProfileController@orders');
            Route::get('profil/colloques', 'Frontend\ProfileController@colloques');
            Route::get('profil/subscriptions', 'Frontend\ProfileController@subscriptions');
            Route::get('profil/inscription/{id}', 'Frontend\ProfileController@inscription');

            /* Update user adresse via ajax  */
            Route::post('ajax/adresse/{id}', 'Api\User\AdresseController@ajaxUpdate');

        });

        /* *
         * Shop routes for frontend shop
         * */
        Route::get('/', 'Frontend\Shop\ShopController@index');
        Route::match(['get', 'post'], 'products', 'Frontend\Shop\ShopController@products');
        Route::match(['get', 'post'], 'search', 'Frontend\Shop\ShopController@search');
        Route::get('categorie/{id}', 'Frontend\Shop\ShopController@categorie');
        Route::get('product/{id}', 'Frontend\Shop\ShopController@show');
        Route::get('archives', 'Frontend\Colloque\ColloqueController@archives');
        Route::get('subscribe', 'Frontend\Shop\ShopController@subscribe');
        Route::get('unsubscribe', 'Frontend\Shop\ShopController@unsubscribe');

        Route::match(['get', 'post'], 'sort', 'Frontend\Shop\ShopController@sort');
        Route::get('label/{id}/{label}', 'Frontend\Shop\ShopController@label');

        Route::group(['middleware' => ['auth','pending','cart','checkout']], function () {

            /* Checkout routes for frontend shop  */
            Route::get('checkout/cart',  'Frontend\Shop\CheckoutController@cart');
            Route::get('checkout/billing',  'Frontend\Shop\CheckoutController@billing');
            Route::match(['get', 'post'],'checkout/resume', 'Frontend\Shop\CheckoutController@resume');
            Route::get('checkout/confirm',  'Frontend\Shop\CheckoutController@confirm');
            Route::match(['get', 'post'],'checkout/send', 'Frontend\Shop\CheckoutController@send');
        });

        /* Cart routes for frontend shop  */
        Route::post('cart/addProduct', 'Frontend\Shop\CartController@addProduct');
        Route::post('cart/removeProduct', 'Frontend\Shop\CartController@removeProduct');
        Route::post('cart/quantityProduct', 'Frontend\Shop\CartController@quantityProduct');
        Route::post('cart/applyCoupon', 'Frontend\Shop\CartController@applyCoupon');

        Route::post('cart/addAbo', 'Frontend\Shop\AboController@addAbo');
        Route::post('cart/removeAbo', 'Frontend\Shop\AboController@removeAbo');
        Route::post('cart/quantityAbo', 'Frontend\Shop\AboController@quantityAbo');

    });

    Route::group(['prefix' => 'bail'], function () {

        Route::get('/', array('uses' => 'Frontend\BailController@index'));
        Route::get('page/{slug}/{var?}', array('uses' => 'Frontend\BailController@page'));
        Route::get('lois', array('uses' => 'Frontend\BailController@lois'));
        Route::get('jurisprudence', array('uses' => 'Frontend\BailController@jurisprudence'));
        Route::get('doctrine', array('uses' => 'Frontend\BailController@doctrine'));
        Route::get('calcul', array('uses' => 'Frontend\BailController@calcul'));
        Route::post('loyer', 'Frontend\Bail\CalculetteController@loyer');
        Route::get('unsubscribe', 'Frontend\BailController@unsubscribe');

    });

    Route::group(['prefix' => 'matrimonial'], function () {

        Route::get('/', array('uses' => 'Frontend\MatrimonialController@index'));
        Route::get('page/{slug}/{var?}', array('uses' => 'Frontend\MatrimonialController@page'));
        Route::get('jurisprudence', array('uses' => 'Frontend\MatrimonialController@jurisprudence'));
        Route::get('newsletter/{id?}', array('uses' => 'Frontend\MatrimonialController@newsletter'));
        Route::get('unsubscribe', 'Frontend\MatrimonialController@unsubscribe');
    });

});

Route::group(['prefix' => 'team' , 'middleware' => ['auth','team']], function () {
    Route::get('/','Team\TeamController@index');
    Route::get('order/{id}','Team\Shop\OrderController@show');
    Route::match(['get', 'post'],'orders', 'Team\Shop\OrderController@index');
});

/* *
* Administration routes
* */
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
    Route::match(['get', 'post'],'search/user', 'Backend\SearchController@user');
    Route::get('search/adresse', 'Backend\SearchController@adresse');

    Route::get('search', 'Backend\SearchController@search');

    Route::get('user/getAdresse/{id}', 'Api\User\UserController@getAdresse');
    Route::get('user/getUser/{id}', 'Api\User\UserController@getUser');
    Route::get('adresse/getAdresse/{id}', 'Api\User\AdresseController@getAdresse');

    Route::resource('faq', 'Backend\Bail\FaqController');
    Route::get('question/create/{categorie}', 'Backend\Bail\QuestionController@create');
    Route::resource('question', 'Backend\Bail\QuestionController');

    Route::resource('calculette/ipc', 'Backend\Bail\CalculetteIpcController');
    Route::resource('calculette/taux', 'Backend\Bail\CalculetteTauxController');

    /*
    |--------------------------------------------------------------------------
    | User and Adresse Backend Routes
    |--------------------------------------------------------------------------
    */

    Route::post('adresse/convert','Backend\User\AdresseController@convert');
    Route::post('adresse/livraison','Backend\User\AdresseController@livraison');
    Route::get('adresse/make/{id}','Backend\User\AdresseController@make');
    Route::resource('adresse', 'Backend\User\AdresseController');
    
    Route::get('users', 'Backend\User\UserController@users');
    Route::resource('user', 'Backend\User\UserController');

    Route::post('duplicate/assign','Backend\User\DuplicateController@assign');
    Route::resource('duplicate', 'Backend\User\DuplicateController');

    /*
    |--------------------------------------------------------------------------
    | Ajax specialisations Routes
    |--------------------------------------------------------------------------
    */

    Route::get('specialisation/search', 'Backend\User\SpecialisationController@search');
    Route::delete('specialisation/destroy', 'Backend\User\SpecialisationController@destroy');
    Route::resource('specialisation', 'Backend\User\SpecialisationController');

    /*
    |--------------------------------------------------------------------------
    | Ajax members Routes
    |--------------------------------------------------------------------------
    */

    Route::get('member/search', 'Backend\User\MemberController@search');
    Route::delete('member/destroy', 'Backend\User\MemberController@destroy');
    Route::resource('member', 'Backend\User\MemberController');

    /*
    |--------------------------------------------------------------------------
    | Inscriptions and colloque Routes
    |--------------------------------------------------------------------------
    */

    Route::match(['get', 'post'], 'inscription/colloque/{id}', 'Backend\Colloque\InscriptionController@colloque');

    Route::get('inscription/desinscription/{id}', 'Backend\Colloque\InscriptionController@desinscription');
    Route::get('inscription/create/{id?}', 'Backend\Colloque\InscriptionController@create');
    Route::get('inscription/add/{group_id}', 'Backend\Colloque\InscriptionController@add');
    Route::get('inscription/generate/{id}', 'Backend\Colloque\InscriptionController@generate');
    Route::post('inscription/restore/{id}', 'Backend\Colloque\InscriptionController@restore');
    Route::get('inscription/groupe/{id}', 'Backend\Colloque\InscriptionController@groupe');
    Route::post('inscription/destroygroup/{id}', 'Backend\Colloque\InscriptionController@destroygroup');
    Route::post('inscription/type', 'Backend\Colloque\InscriptionController@inscription');
    Route::post('inscription/make', ['middleware' => 'already', 'uses' => 'Backend\Colloque\InscriptionController@make']);
    Route::post('inscription/edit', 'Backend\Colloque\InscriptionController@edit');
    Route::post('inscription/push', 'Backend\Colloque\InscriptionController@push');
    Route::post('inscription/change', 'Backend\Colloque\InscriptionController@change');
    Route::post('inscription/send', 'Backend\Colloque\InscriptionController@send');
    Route::post('inscription/presence', 'Backend\Colloque\InscriptionController@presence');
    Route::resource('inscription', 'Backend\Colloque\InscriptionController');

    Route::get('inscription/rappels/{id}','Backend\Colloque\RappelController@rappels');
    Route::get('inscription/rappel/make/{id}','Backend\Colloque\RappelController@make');
    Route::post('inscription/rappel/send','Backend\Colloque\RappelController@send');
    Route::resource('inscription/rappel','Backend\Colloque\RappelController');
    Route::get('colloque/archive/{year}', 'Backend\Colloque\ColloqueController@archive');

    Route::post('sondage/remove','Backend\Sondage\SondageController@remove');
    Route::post('sondage/sorting','Backend\Sondage\SondageController@sorting');
    Route::post('sondage/send','Backend\Sondage\SondageController@send');
    Route::resource('sondage', 'Backend\Sondage\SondageController');
    Route::resource('avis', 'Backend\Sondage\AvisController');
    Route::match(['get', 'post'], 'reponse/{id}', 'Backend\Sondage\ReponseController@show');

    Route::resource('sondageavis', 'Backend\Sondage\SondageAvisController');
    
    // Add, edit, delete items for colloque
    Route::resource('colloque', 'Backend\Colloque\ColloqueController');
    Route::resource('price', 'Backend\Colloque\PriceController');
    Route::resource('option', 'Backend\Colloque\OptionController');
    Route::resource('groupoption', 'Backend\Colloque\GroupOptionController');
    Route::resource('occurrence', 'Backend\Colloque\OccurrenceController');
    Route::resource('compte', 'Backend\Colloque\CompteController');

    Route::get('document/{colloque_id}/{doc}', 'Backend\Colloque\DocumentController@show');
    Route::resource('document', 'Backend\Colloque\DocumentController');

    Route::get('attestation/colloque/{id}', 'Backend\Colloque\AttestationController@colloque');
    Route::get('attestation/inscription/{id}', 'Backend\Colloque\AttestationController@inscription');
    Route::resource('attestation', 'Backend\Colloque\AttestationController');

    Route::post('export/inscription', 'Backend\ExportController@inscription');
    Route::post('export/badges', 'Backend\ExportController@badges');
    Route::get('export/view', 'Backend\ExportController@view');
    Route::get('export/generate', 'Backend\ExportController@generate');
    Route::get('export/qrcodes/{id}', 'Backend\ExportController@qrcodes');
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

    Route::get('menus/{site}','Backend\MenuController@index');
    Route::get('menu/create/{site}','Backend\MenuController@create');
    Route::resource('menu', 'Backend\MenuController');

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

    Route::post('stock/change', 'Backend\Shop\StockController@update');
    Route::get('stock/product/{id}', 'Backend\Shop\StockController@product');
    Route::get('stock/export/{id}', 'Backend\Shop\StockController@export');

    Route::match(['get', 'post'],'orders', 'Backend\Shop\OrderController@index');
    Route::post('order/edit', 'Backend\Shop\OrderController@edit');
    Route::post('order/export', 'Backend\Shop\OrderController@export');
    Route::match(['get', 'post'],'orders/resume', 'Backend\Shop\OrderController@resume');
    Route::post('order/restore/{id}', 'Backend\Shop\OrderController@restore');
    Route::post('order/generate', 'Backend\Shop\OrderController@generate');

    Route::post('order', ['middleware' => 'strip', 'uses' => 'Backend\Shop\OrderController@store']);
    Route::get('order','Backend\Shop\OrderController@index');
    Route::get('order/create','Backend\Shop\OrderController@create');
    Route::get('order/{id}','Backend\Shop\OrderController@show');
    Route::put('order/{id}','Backend\Shop\OrderController@update');
    Route::delete('order/{id}','Backend\Shop\OrderController@destroy');

    //Route::resource('order', 'Backend\Shop\OrderController');

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
    Route::get('fileJson/{id?}',  ['uses' => 'Backend\UploadController@fileJson']);

    Route::post('files', ['uses' => 'Backend\FileController@files']);
    Route::get('tree', ['uses' => 'Backend\FileController@tree']);
    Route::get('export', ['uses' => 'Backend\FileController@tree']);
    Route::post('files/delete', ['uses' => 'Backend\FileController@delete']);
    Route::post('files/crop', ['uses' => 'Backend\FileController@crop']);

    Route::get('convert', ['uses' => 'Backend\FileController@convert']);

    /*
    |--------------------------------------------------------------------------
    | Seminaire Routes
    |--------------------------------------------------------------------------
    */

    Route::resource('seminaire', 'Backend\Content\SeminaireController');
    Route::resource('subject', 'Backend\Content\SubjectController');

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

    Route::match(['get', 'post'],'factures/{id}', 'Backend\Abo\AboFactureController@index');
    Route::get('facture/generate/{id}', 'Backend\Abo\AboFactureController@generate');
    Route::get('facture/bind/{id}', 'Backend\Abo\AboFactureController@bind');
    Route::resource('facture', 'Backend\Abo\AboFactureController');

    Route::get('rappel/generate/{id}', 'Backend\Abo\AboRappelController@generate');
    Route::get('rappel/bind/{id}', 'Backend\Abo\AboRappelController@bind');
    Route::resource('rappel', 'Backend\Abo\AboRappelController');

    Route::get('abo/desinscription/{id}', 'Backend\Abo\AboController@desinscription');
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

    Route::get('blocs/{site}','Backend\Content\BlocController@index');
    Route::get('bloc/create/{site}','Backend\Content\BlocController@create');
    Route::resource('bloc',      'Backend\Content\BlocController');

    Route::get('pages/{site}','Backend\Content\PageController@index');
    Route::get('page/create/{site}','Backend\Content\PageController@create');
    Route::post('page/sorting','Backend\Content\PageController@sorting');
    Route::resource('page',      'Backend\Content\PageController');

    Route::post('pagecontent/sorting','Backend\Content\PageContentController@sorting');
    Route::get('pagecontent/{type}/{page}','Backend\Content\PageContentController@index');
    Route::resource('pagecontent', 'Backend\Content\PageContentController');

    Route::resource('author',      'Backend\Content\AuthorController');
    Route::resource('location',    'Backend\Colloque\LocationController');
    Route::resource('organisateur','Backend\Colloque\OrganisateurController');

    Route::match(['get', 'post'], 'email', 'Backend\EmailController@index');
    Route::get('email/{id}','Backend\EmailController@show');
  
    /*
    |--------------------------------------------------------------------------
    | Backend subscriptions, newsletters and campagnes Routes
    |--------------------------------------------------------------------------
    */

    Route::get('ajax/arret/{id}',   'Backend\Content\ArretController@simple');
    Route::get('ajax/arrets/{id?}', 'Backend\Content\ArretController@arrets');
    Route::get('ajax/analyses/{id}','Backend\Content\AnalyseController@simple');
    Route::get('ajax/categories/{id?}',   'Backend\Content\CategorieController@categories');
    Route::post('ajax/categorie/arrets','Backend\Content\CategorieController@arrets');

});

/*
|--------------------------------------------------------------------------
| Authentication routes...
|--------------------------------------------------------------------------
*/

Auth::routes();
Route::get('password/new', 'Auth\PasswordController@getNew');
Route::post('password/define', 'Auth\PasswordController@postDefine');

/*
 * Only for development
 * */
if (App::environment('local'))
{
    require base_path('routes/dev.php');
}
