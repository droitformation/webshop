<?php

Route::group(['prefix' => 'preview', 'middleware' => ['auth','administration']], function () {

    Route::get('contact', function () {

        $sites = new \App\Droit\Site\Entities\Site();
        $sites = $sites->all();

        $data = [
            'site'     => $sites->first(),
            'remarque' => 'Dapibus ante accumasa laoreet mauris nostra torquenté vehicula non interdùm, vehiculâ suscipit alèquam. Lorem ad quîs j\'libéro pharétra vivamus mollis ultricités ut, vulputaté ac pulvinar èst commodo aenanm pharétra cubilia, lacus aenean pharetra des ïd quisquées mauris varius sit. Mie dictumst nûllam çurcus molestié imperdiet vestibulum suspendisse tempor habitant sit pélléntésque sit çunc, primiés ?',
            'name'     => 'Cindy Leschaud',
            'email'    => 'cindy.leschaud@gmail.com'
        ];

        return view('emails.contact')->with($data);
    });

    Route::get('newsletter', function () {

        $sites = new \App\Droit\Site\Entities\Site();
        $sites = $sites->all();

        $data = [
            'site'  => $sites->first(),
            'token' => '#',
            'newsletter_id' => '1'
        ];

        return view('emails.confirmation')->with($data);

    });

    Route::get('notification', function () {

        $model = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');

        $inscriptions = $model->getAll(10);
        $inscription  = !$inscriptions->isEmpty() ? $inscriptions->first() : null;

        $data = [
            'name'     => $inscription->inscrit->name,
            'colloque' => $inscription->colloque->titre,
            'what'     => 'inscription',
            'link'     => 'admin/inscription/colloque/'.$inscription->colloque->id
        ];

        return view('emails.notification')->with($data);

    });

    Route::get('sondage/{id?}', function ($id = null) {

        $model = \App::make('App\Droit\Sondage\Repo\SondageInterface');

        if($id) {
            $sondage =  $model->find($id);
        }
        else {
            $sondages = $model->getAll();
            $sondage  = !$sondages->isEmpty() ? $sondages->first() : null;
        }

        if(!$sondage) {
            return 'Aucun sondage à afficher';
        }

        $url    = base64_encode(json_encode(['sondage_id' => $sondage->id, 'email' => config('mail.from.address'),'isTest'  => 1]));
        $donnes = ['sondage' => $sondage, 'email' => config('mail.from.address'), 'url' => $url];

        return view('emails.sondage')->with($donnes);
        
    });

    Route::get('reminder', function ($id = null) {

        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $reminder = factory(App\Droit\Reminder\Entities\Reminder::class)->make(['model_id' => $colloque->id]);

        $data = [
            'reminder' => $reminder,
            'item'     => $colloque
        ];

        return view('emails.reminder')->with($data);

    });

    Route::get('order', function ($id = null) {

        $model = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');

        if($id) {
            $order =  $model->find($id);
        }
        else {
            $orders = $model->getLast(1);
            $order  = !$orders->isEmpty() ? $orders->first() : null;
        }

        if(!$order) {
            return 'Aucune commande à afficher';
        }

        $data = [
            'title'     => 'Votre commande sur publications-droit.ch',
            'concerne'  => 'Votre commande',
            'logo'      => 'facdroit.png',
            'order'     => $order,
            'products'  => $order->products->groupBy('id'),
            'date'      => \Carbon\Carbon::now()->formatLocalized('%d %B %Y')
        ];

        return view('emails.shop.confirmation')->with($data);

    });

    Route::get('inscription/{id?}', function ($id = null) {

        $model = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');

        if($id) {
            $inscription =  $model->find($id);
        }
        else {
            $inscriptions = $model->getAll(10);
            $inscription  = !$inscriptions->isEmpty() ? $inscriptions->first() : null;
        }

        if(!$inscription) {
            return 'Aucune inscription à afficher';
        }

        $data = [
            'title'        => 'Votre inscription sur publications-droit.ch',
            'concerne'     => 'Inscription',
            'annexes'      => $inscription->colloque->annexe,
            'colloque'     => $inscription->colloque,
            'inscription'  => $inscription,
            'date'         => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
        ];

        if($inscription->group_id)
        {
            $data['participants'] = $inscription->groupe->participant_list;
            $data['user']         = $inscription->groupe->user;
        }

        if($inscription->user_id)
        {
            $data['user'] = $inscription->user;
        }

        return view('emails.colloque.confirmation')->with($data);
    });

    Route::get('rappel/{id?}', function ($id = null) {

        $model = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');

        if($id) {
            $inscription =  $model->find($id);
        }
        else {
            $inscriptions = $model->getAll(10);
            $inscription  = !$inscriptions->isEmpty() ? $inscriptions->first() : null;
        }

        if(!$inscription) {
            return 'Aucune inscription à afficher';
        }

        $data = [
            'title'        => 'Votre inscription sur publications-droit.ch',
            'concerne'     => 'Inscription',
            'annexes'      => $inscription->colloque->annexe,
            'colloque'     => $inscription->colloque,
            'inscription'  => $inscription,
            'date'         => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
        ];

        if($inscription->group_id)
        {
            $data['participants'] = $inscription->groupe->participant_list;
            $data['user']         = $inscription->groupe->user;
        }

        if($inscription->user_id)
        {
            $data['user'] = $inscription->user;
        }

        return view('emails.colloque.rappel')->with($data);
    });


    Route::get('abonnement/{id?}', function ($id = null) {

        $model = new \App\Droit\Abo\Entities\Abo_users();
        
        if($id) {
            $abonnement =  $model->find($id);
        }
        else 
        {
            $abonnements = $model->where('status','=','abonne')->orderBy('id','desc')->take(1)->get();
            $abonnement  = !$abonnements->isEmpty() ? $abonnements->first() : null;
        }
        
        $data = [
            'title'     => 'Votre demande d\'abonnement sur publications-droit.ch',
            'concerne'  => 'Votre demande d\'abonnement',
            'logo'      => 'facdroit.png',
            'abos'      => collect([$abonnement]),
            'total'     => number_format((float) (20000/100), 2, '.', ''),
            'user'      => $abonnement->user_facturation,
            'date'      => \Carbon\Carbon::now()->formatLocalized('%d %B %Y')
        ];

        return view('emails.shop.demande')->with($data);

    });

    Route::get('aborappel', function () {

        $model    = \App::make('App\Droit\Abo\Repo\AboFactureInterface');
        $factures = $model->getAll(303);
        $facture  = !$factures->isEmpty() ? $factures->first() : null;

        if($facture)
        {
            $rappel = $facture->rappels->sortBy('created_at')->last();

            $data = [
                'title'       => 'Abonnement sur publications-droit.ch',
                'concerne'    => 'Rappel',
                'abonnement'  => $facture->abonnement,
                'abo'         => $facture->abonnement->abo,
                'date'        => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
            ];

            return view('emails.abo.rappel')->with($data);
        }

        return 'Aucune rappel à afficher';
    });

});