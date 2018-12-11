<?php
/* ============================================
 * Transfert routes
 * ddt, rca into shop
 ============================================ */

Route::get('transfert','HomeController@transfert')->middleware(['auth','administration']);
Route::post('dotransfert','HomeController@dotransfert')->middleware(['auth','administration']);