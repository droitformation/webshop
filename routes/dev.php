<?php
/* ============================================
 * Test routes
 ============================================ */


Route::get('mapped', function () {

    $CampagneInterface = \App::make('App\Droit\Newsletter\Repo\NewsletterCampagneInterface');

    $campagnes = $CampagneInterface->getNotSent(2);

    $arrets = $campagnes->flatMap(function ($campagne) {
            return $campagne->content;
        })->map(function ($content, $key) {

            if($content->arret_id)
                return $content->arret_id ;

            if($content->groupe_id > 0)
               return $content->groupe->arrets_groupes->pluck('id')->all();

        })->filter(function ($value, $key) {
            return !empty($value);
    });

    echo '<pre>';
    print_r($campagnes);
    echo '</pre>';

});


Route::get('testing', function() {
    
    $worker  = \App::make('App\Droit\Inscription\Worker\InscriptionWorker');
    $groups       = \App::make('App\Droit\Inscription\Repo\GroupeInterface');
    $generator    = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
    $colloques    = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
    $users        = \App::make('App\Droit\User\Repo\UserInterface');

    $adresses    = \App::make('App\Droit\Adresse\Repo\AdresseInterface');
    $abos        = \App::make('App\Droit\Abo\Repo\AboRappelInterface');
    $factures    = \App::make('App\Droit\Abo\Repo\AboFactureInterface');

/*
    $colloque = $colloques->find(39);
    $adresse  = $adresses->find(6005);
    $user     = $users->find(710);

    $rappels  = $factures->getFacturesAndRappels(292);

    $order = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
    $items  = $order->getLast(2);*/

    $inscrit  = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
    $inscriptions = $inscrit->getByColloque(39,false, false);

    $names = collect([
        'civilite_title'   => 'Civilité',
        'name'             => 'Nom et prénom',
        'email'            => 'E-mail',
        'profession_title' => 'Profession',
        'company'          => 'Entrprise',
        'telephone'        => 'Téléphone',
        'mobile'           => 'Mobile',
        'adresse'          => 'Adresse',
        'cp'               => 'CP',
        'complement'       => 'Complément d\'adresse',
        'npa'              => 'NPA',
        'ville'            => 'Ville',
        'canton_title'     => 'Canton',
        'pays_title'       => 'Pays'
    ]);

    $users = $inscriptions->map(function ($inscription, $key) use ($names) {
        $data = [
            'Present'     => $inscription->present ? 'Oui' : '',
            'Numéro'      => $inscription->inscription_no,
            'Prix'        => $inscription->price_cents,
            'Status'      => $inscription->status_name['status'],
            'Date'        => $inscription->created_at->format('m/d/Y'),
            'Participant' => ($inscription->group_id > 0 ? $inscription->participant->name : ''),
        ];

        $user = $inscription->inscrit;

        // Adresse columns
        if($user && !$user->adresses->isEmpty())
        {
            $data += $names->map(function ($item, $key) use ($user) {
                return $user->adresses->first()->$key;
            })->toArray();
        }

        // Options checkbox
        if(!$inscription->user_options->isEmpty())
        {
            $data['checkbox'] = $inscription->user_options->load('option')->where('groupe_id', null)->implode('option.title', PHP_EOL);
        }

        return $data;
    });


    echo '<pre>';
    print_r($users);
    echo '</pre>';

    /*
        $generator->stream = true;
        return $generator->factureOrder(2922);*/


    /*
        $collection = $rappels->map(function ($item, $key) {
            $rappel = $item->rappels->sortByDesc('created_at')->first();
            $pdf    = 'files/abos/rappel/292/rappel_'.$rappel->id.'_'.$rappel->abo_facture_id.'.pdf';

            if(\File::exists($pdf)){ return $pdf; }

        })->all();

        $files = \File::files('files/abos/rappel/292');
        $get   = array_intersect($collection,$files);

        echo '<pre>';
        print_r($collection);
        print_r($files);
        print_r($get);
        echo '</pre>';*/


    //$generator->stream = true;
    //$generate = new \App\Droit\Generate\Entities\Generate($inscriptio);
    //return $generator->make('bon', $inscriptio);
/*    $inscriptio = $inscription->find(9547);

    $today = \Carbon\Carbon::today();
    $today = \Carbon\Carbon::parse('2016-09-16');

    $presence = $inscriptio->occurrences->filter(function ($value, $key) use ($today){
        return $value->start_at == $today;
    });

    $inscriptio->occurrences()->updateExistingPivot($presence->first()->id, ['present' => 1]);*/

    //echo md5('cindy');

});

Route::get('cartworker', function()
{

    $user   = Auth::user()->load('adresses');
    $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorker');
    //$worker = \App::make('App\Droit\Abo\Worker\AboWorker');

    $data[] = [
        'abo_id'         => 1,
        'exemplaires'    => 1,
        'adresse_id'     => 2,
        'status'         => 'abonne',
        'renouvellement' => 'auto'
    ];

    $abos = $worker->getAboData($data);
    //$abos = $abo->max(['abo_id' => 4]) +1;

    echo '<pre>';
    print_r($abos);
    echo '</pre>';exit();

    /*
        $adresse_specialisation = new \App\Droit\Adresse\Entities\Adresse_specialisation();

        $specialisations = $adresse_specialisation->where('adresse_id','=',1)->whereIn('specialisation_id',[1,71])-et();
        /*
            $query = 'SELECT adresse_id, COUNT(specialisation_id)
                        FROM adresse_specialisations
                        WHERE
                             (adresse_id = 1 AND specialisation_id = 1)
                          OR (adresse_id = 1 AND specialisation_id = 70)
                           OR (adresse_id = 1 AND specialisation_id = 71)
                        GROUP by adresse_id
                        HAVING COUNT(specialisation_id) > 1';

            $query = 'SELECT d.adresse_id, d.specialisation_id
                        FROM   adresse_specialisations d
                        JOIN   adresse_specialisations x ON d.adresse_id = x.adresse_id
                        JOIN   adresse_specialisations y ON d.adresse_id = y.adresse_id
                        WHERE  x.specialisation_id = 1
                        AND    y.specialisation_id = 70';

            $users =  DB::select( DB::raw($query)


            echo '<pre>';
            print_r($users);
            echo '</pre>';exit;
        /*
            $inscription  = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
            $generator    = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');

            $item = $inscription->find(105);

            $item->load('colloque');
            $annexes = $item->colloque->annexe;

            // Generate annexes if any
            if(empty($item->documents) && !empty($annexes))
            {
                $generator->setInscription($item)->generate($annexes);
            }*/

//    $repo      = \App::make('App\Droit\Shop\Product\Repo\ProductInterface');
//    $item      = $repo->find(82);
//    $attribute = $item->attributes->where('id',3);

/*    $abo        = \App::make('App\Droit\Abo\Repo\AboUserInterface');
    $abonnement = $abo->find(1);

    $generator  = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
    $generator->stream = true;
    return $generator->factureAbo($abonnement);*/

/*    $worker = \App::make('App\Droit\Abo\Worker\AboWorkerInterface');

    $files = [
        'files/abos/facture_REV-1_16.pdf',
        'files/abos/facture_REV-2_21.pdf',
        'files/abos/facture_RJN-288_17.pdf',
        'files/abos/facture_RJN-288_18.pdf',
        'files/abos/facture_RJN-289_20.pdf'
    ];

    $worker->merge($files, 'binding');*/


    /*

        $products = $item->products->groupBy('id');

        foreach($products as $id => $product)
        {
            $count[$id] = $product->sum(function ($item) {
                return count($item['id']);
            });
        }*/
   // $duplicates = \App::make('App\Droit\User\Repo\DuplicateInterface');
   // $duplicate  = $duplicates->find(762);

/*    $gro = new \App\Droit\Inscription\Entities\Groupe();
    $groupe = $gro->find(3);
    $groupe->load('colloque','user','inscriptions','inscriptions.participant');

    $Inscriptions = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');

    $list = $Inscriptions->getByColloque(39);

    $simple = $list->map(function ($value, $key) {
        $simple = !$value->group_id ? true : false;
    });

    foreach($list as $item)
    {
        $simple = !$item->group_id ? $item : $item->groupe;
        $collection[$simple->id] = $item;
    }

    $grouped = $list->filter(function ($value, $key) {
        return $value->group_id;
    })->groupBy('group_id')->map(function ($item, $key) {
        return $item->first()->groupe->load('inscriptions');
    })->values();*/

    $abofactures  = \App::make('App\Droit\Abo\Repo\AboFactureInterface');
    $abousers      = \App::make('App\Droit\Abo\Repo\AboUserInterface');
    $inscriptions  = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
    $groupes       = new \App\Droit\Inscription\Entities\Groupe();
    $inscription   = $inscriptions->find(9547);
/*    $groupe        = $groupes->find(1);


    $abofacture    = $abofactures->find(939);
    $abouser   = $abousers->find(531);*/

    $generator     = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');

    //$job = new \App\Jobs\MakeDocument($inscription);
    //$job->handle();
    $generator->stream = true;

    //$generate = new \App\Droit\Generate\Entities\Generate($abofacture);

    exit;

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

Route::get('messageadmin', function()
{
    $sites = new App\Droit\Site\Entities\Site();
    $site  = $sites->find(1);

    $data = [
        'name'      => 'Cindy Leschaud',
        'site'      => $site,
        'email'     => 'cindy.leschaud@gmail.com',
        'remarque'  => '<h3>Quis consectetur  aenanm mié àc ornare fermentum cél leçtus vivérra séd</h3><p> himenaeos
                        interdum dapibus nulla ût nètus cursus consectetur lacinia curabitur suscipit dolor nibh mlius,
                        rhoncüs donec égét.</p> <p>Platea sociosqu potentié proîn habitassé c\'est-a-dire curabitur lorem fermentum
                        potenti ïpsum vulputaté primiés l\'sagittis interdùm phasellus quîs grâvida aenean témpor lilitoxic
                        lacinia dicûm 19 605€ condimentum grâvida purus m\'amèt, Frînglilia porttitor curabitur proin est
                        èiam convallis léo tincidunt ût ac métus vestibulum elementum consequat pulvinar.</p>'
    ];

    return View::make('emails.contact', $data);

});

Route::get('notification', function()
{
    $orders  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
    $order   = $orders->getLast(1);
    $order   = $order->first();

    $job = new App\Jobs\SendOrderConfirmation($order);

    app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);

    exit;

    setlocale(LC_ALL, 'fr_FR.UTF-8');
    $date   = \Carbon\Carbon::now()->formatLocalized('%d %B %Y');
    $title  = 'Votre commande sur publications-droit.ch';
    $logo   = 'facdroit.png';
    $orders  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');

    $order = $orders->find(2922);
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

Route::get('demande', function()
{
    $model = \App::make('App\Droit\Abo\Repo\AboUserInterface');
    $abos  = $model->allByAdresse(4983);

    $job = new App\Jobs\NotifyAdminNewAbo($abos);

    app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);

    //return View::make('emails.shop.demande', $data);

});

Route::get('registration', function()
{
    setlocale(LC_ALL, 'fr_FR.UTF-8');

    $date   = \Carbon\Carbon::now()->formatLocalized('%d %B %Y');
    $title  = 'Votre inscription sur publications-droit.ch';
    $logo   = 'facdroit.png';

    $inscription = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
    $groups      = \App::make('App\Droit\Inscription\Repo\GroupeInterface');

    $inscrit     = $inscription->find(3);
    $group       = $groups->find(3);

    $data = [
        'title'        => $title,
        'concerne'     => 'Inscription',
        'logo'         => $logo,
        'annexes'      => $inscrit->colloque->annexe,
        'colloque'     => $inscrit->colloque,
        'user'         => $inscrit->user,
        'date'         => $date,
    ];

/*    $data1 = [
        'title'        => $title,
        'concerne'     => 'Inscription',
        'logo'         => $logo,
        'annexes'      => $group->colloque->annexe,
        'colloque'     => $group->colloque,
        'user'         => $group->user,
        'participants' => $group->participant_list,
        'date'         => $date,
    ];*/

    return View::make('emails.colloque.confirmation', $data);

});

Route::get('notifyadmin', function()
{

    $inscription = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
    $inscrit     = $inscription->find(3);

/*    $orders = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
    $order  = $orders->find(2930);*/

    $inscritpt = [
        'name' => $inscrit->inscrit->name,
        'colloque' => $inscrit->colloque->titre,
        'what' => 'inscription',
        'link' => 'admin/inscription/colloque/'.$inscrit->colloque->id
    ];

/*    $ordered = [
        'name'  => $order->user->name,
        'what'  => 'commande',
        'order' => $order->order_no,
        'link'  => 'admin/orders'
    ];*/

    $infos = [
        'name'     => $inscrit->inscrit->name,
        'colloque' => $inscrit->colloque->titre,
        'what'     => 'inscription',
        'link'     => 'admin/inscription/colloque/'.$inscrit->colloque->id
    ];

    \Mail::send('emails.notification', $infos, function ($m) {
        $m->from('droit.formation@unine.ch', 'Administration');
        $m->to('cindy.leschaud@gmail.com', 'Administration')->subject('Notification');
    });

    return View::make('emails.notification',$inscritpt);

});

Route::get('confirmation_newsletter', function()
{
    $sites = new App\Droit\Site\Entities\Site();
    $site  = $sites->find(1);

    $data = [
        'site' => $site,
        'token' => '<xcdsfgreqewfwdb'
    ];

    return View::make('emails.confirmation', $data);
});

Route::get('testproduct', function()
{
    $reminders  = \App::make('App\Droit\Reminder\Repo\ReminderInterface');
    $list = $reminders->toSend();

    foreach($list as $reminder)
    {
        $model = new $reminder->model;

        if ($model instanceof Illuminate\Database\Eloquent\Model)
        {
            $model_id = $reminder->model_id;
            $item     = $model->find($model_id);

      /*      echo '<pre>';
            print_r($item);
            echo '</pre>';exit;*/

            return View::make('emails.reminder', ['reminder' => $reminder, 'item' => $item]);
            exit;
        }
    }

});

Route::get('manager', function()
{
    $manager = App::make('App\Droit\Service\FileWorkerInterface');
    $files   = $manager->listDirectoryFiles('files/logos/');

    echo '<pre>';
    print_r($files);
    echo '</pre>';exit;

});

Route::get('/calculette', function () {

    $model    =  App::make('App\Droit\Calculette\Worker\CalculetteWorkerInterface');
    $facture  = $model->taux_actuel();

    echo '<pre>';
    print_r($facture);
    echo '</pre>';exit;

});

Route::get('factory', function()
{
    $make = new \tests\factories\ObjectFactory();
    $colloque = $make->colloque();

    echo '<pre>';
    print_r($colloque);
    echo '</pre>';

});

Route::get('merge', function () {

      // Export adresses
    $exporter = new \App\Droit\Generate\Export\ExportAdresse();
    $exporter->merge();
    
});

Route::get('exporter', function () {

    $adresses = [
       [
          [
               0 => 'Marlyse  <strong>GUINAND</strong>',
               1 => 'Geneviève <strong>ROBERT-GRANDPIERRE</strong>',
               2 => 'Marilyn <strong>DUCOMMUN</strong>',
           ],
           [
               0 => 'Anne Elisabeth <strong>MICHEL</strong>',
               1 => 'Annie <strong>ROCHAT PAUCHARD</strong>',
               2 => 'Jacques <strong>ROSSAT</strong>',
           ],
           [
               0 => 'Charlotte <strong>SANDOZ</strong>',
               1 => 'Bruno <strong>AMSLER</strong>',
               2 => 'Christiane <strong>TERRIER</strong>',
           ],
           [
               0 => 'Alexandre <strong>BRODARD</strong>',
               1 => 'Alexansddre <strong>BRODsv-fewfewfARD</strong>',
               2 => 'Béatrice <strong>WISEMAN</strong>',
           ],
       ]
    ];

    $data = config('badge.zweckform27') + ['data' => $adresses];

/*    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit;*/


    return \PDF::loadView('backend.export.badge', $data)->setPaper('a4')->stream('badges_.pdf');

});
