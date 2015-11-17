<?php
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
});

Route::get('myaddress', function()
{
    factory(App\Droit\Adresse\Entities\Adresse::class,200)->create();

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
