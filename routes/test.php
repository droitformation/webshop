<?php
/* ============================================
 * Test routes
 *
 * Views pour commandes, abonnements, colloques
 * Commandes => resources/views/templates/shop
 * Abos: resources/views/templates/abonnement
 * Colloques: resources/views/templates/colloque
 ============================================ */

Route::get('colloque_facture', function () {

    $make     = new \tests\factories\ObjectFactory();
    $colloque = $make->makeInscriptions(1);

    $inscription = $colloque->inscriptions->first();

    $generator = new \App\Droit\Generate\Pdf\PdfGenerator();
    $generator->stream = true;

    return $generator->make('facture', $inscription);

    // resources/views/templates/colloque/facture.blade.php

})->middleware('auth');

Route::get('colloque_bon', function () {

    $make     = new \tests\factories\ObjectFactory();
    $colloque = $make->makeInscriptions(1);

    $inscription = $colloque->inscriptions->first();

    $generator = new \App\Droit\Generate\Pdf\PdfGenerator();
    $generator->stream = true;

    return $generator->make('bon', $inscription);

    // resources/views/templates/colloque/bon.blade.php

})->middleware('auth');

Route::get('commande_facture', function () {

    $make = new \tests\factories\ObjectFactory();

    $orders = $make->order(1);
    $order  = $orders->first();

    $generator = new \App\Droit\Generate\Pdf\PdfGenerator();
    $generator->stream = true;

    return $generator->factureOrder($order);

    // resources/views/templates/shop/facture.blade.php

})->middleware('auth');

Route::get('abo_facture', function () {

    // Make abo
    $make   = new \tests\factories\ObjectFactory();
    $abo    = $make->makeAbo();

    $abonnement = $make->makeUserAbonnement($abo);

    $make->abonnementFacture($abonnement);

    $facture = $abonnement->factures->first();

    $generator = new \App\Droit\Generate\Pdf\PdfGenerator();
    $generator->stream = true;

    return $generator->makeAbo('facture',$facture);

    // resources/views/templates/abonnement/facture.blade.php

})->middleware('auth');
