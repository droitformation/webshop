<?php

Route::group(['prefix' => 'preview', 'middleware' => ['auth','administration']], function () {

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

    Route::get('inscription', function ($id = null) {

        $model = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');

        if($id) {
            $inscription =  $model->find($id);
        }
        else {
            $inscriptions = $model->getAll(10);
            $inscription  = !$inscriptions->isEmpty() ? $inscriptions->first(function ($value, $key) {
                return $value->user_id;
            }) : null;
        }

        if(!$inscription) {
            return 'Aucune inscription à afficher';
        }

        $data = [
            'title'        => 'Votre inscription sur publications-droit.ch',
            'concerne'     => 'Inscription',
            'annexes'      => $inscription->colloque->annexe,
            'colloque'     => $inscription->colloque,
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

});