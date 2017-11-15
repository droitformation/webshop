<?php

$middleware = !empty(config('newsletter.middlewares')) ? config('newsletter.middlewares') : [];

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::post('unsubscribe', 'Frontend\InscriptionController@unsubscribe');
Route::post('subscribe', 'Frontend\InscriptionController@subscribe')->middleware('honeybot');
Route::get('activation/{token}/{newsletter_id}', 'Frontend\InscriptionController@activation');

Route::get('campagne/{id}', 'Frontend\CampagneController@show');
Route::get('pdf/{id}', 'Frontend\CampagneController@pdf');

Route::group(['prefix' => 'display'], function () {
   // Route::resource('newsletter', 'Frontend\NewsletterController');
    //Route::get('newsletter/campagne/{id}', 'Frontend\NewsletterController@campagne');
    Route::resource('campagne', 'Frontend\CampagneController');
});


Route::group(['middleware' => $middleware], function () {

    /*
    |--------------------------------------------------------------------------
    | Backend Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'build'], function () {

        /*
       |--------------------------------------------------------------------------
       | Backend subscriptions, newsletters and campagnes Routes
       |--------------------------------------------------------------------------
       */

        Route::get('newsletter/archive/{newsletter}/{year}', 'Backend\Newsletter\NewsletterController@archive');
        Route::resource('newsletter', 'Backend\Newsletter\NewsletterController');

        Route::get('campagne/create/{newsletter}', 'Backend\Newsletter\CampagneController@create');
        Route::get('campagne/simple/{id}', 'Backend\Newsletter\CampagneController@simple');
        Route::get('campagne/preview/{id}', 'Backend\Newsletter\CampagneController@preview');
        Route::get('campagne/cancel/{id}', 'Backend\Newsletter\CampagneController@cancel');
        Route::post('campagne/archive', 'Backend\Newsletter\CampagneController@archive');

        Route::resource('campagne', 'Backend\Newsletter\CampagneController');

        // Content building blocs
        Route::post('sorting', 'Backend\Newsletter\ContentController@sorting');
        Route::post('sortingGroup', 'Backend\Newsletter\ContentController@sortingGroup');
        Route::resource('content', 'Backend\Newsletter\ContentController');

        Route::post('send/campagne', 'Backend\Newsletter\SendController@campagne');
        Route::post('send/test', 'Backend\Newsletter\SendController@test');
        Route::post('send/forward', 'Backend\Newsletter\SendController@forward');

        Route::post('clipboard/copy', 'Backend\Newsletter\ClipboardController@copy');
        Route::post('clipboard/paste', 'Backend\Newsletter\ClipboardController@paste');

        Route::resource('subscriber', 'Backend\Newsletter\SubscriberController');
        Route::get('subscribers', ['uses' => 'Backend\Newsletter\SubscriberController@subscribers']);

        Route::resource('statistics', 'Backend\Newsletter\StatsController');

        Route::resource('import', 'Backend\Newsletter\ImportController');
        Route::resource('emails', 'Backend\Newsletter\EmailController');

        Route::post('send/list', 'Backend\Newsletter\ListController@send');
        Route::get('send/confirmation/{id}', 'Backend\Newsletter\ListController@confirmation');
        Route::post('export', 'Backend\Newsletter\ListController@export');
        Route::resource('liste', 'Backend\Newsletter\ListController');

        Route::get('tracking/stats/{id}', 'Backend\Newsletter\TrackingController@stats');

    });

});
