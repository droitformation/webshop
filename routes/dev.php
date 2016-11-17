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
    $abos        = \App::make('App\Droit\Abo\Repo\AboInterface');
    $factures    = \App::make('App\Droit\Abo\Repo\AboFactureInterface');

/*
    $colloque = $colloques->find(39);
    $adresse  = $adresses->find(6005);
    $user     = $users->find(710);

    $rappels  = $factures->getFacturesAndRappels(292);

    $order = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
    $items  = $order->getLast(2);*/
    /*
    $orders  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
    $order   = $orders->find(6);

    $info['Numero']  = $order->order_no;
    $info['Date']    = $order->created_at->format('d.m.Y');
    $info['Montant'] = $order->price_cents.' CHF';
    $info['Port']    = $order->total_shipping;
    $info['Paye']    = $order->payed_at ? $order->payed_at->format('d.m.Y') : '';
    $info['Status']  = $order->status_code['status'];

    // Only details of each order and group by product in orde, count qty
    //$products = $this->free ? $this->hasFreeProducts($order) : $order->products;
    //$grouped  = $products->groupBy('id');

    $grouped = $order->products->groupBy(function ($item, $key) {
        return $item->id.$item->pivot->price.$item->pivot->rabais.$item->pivot->isFree;
    });

    if($order->order_adresse)
    {
        $columns = array_keys(config('columns.names'));
        // Get columns requested from user adresse
        foreach($columns as $column)
        {
            $user[$column] = $order->order_adresse->$column;
        }
    }

    foreach($grouped as $product)
    {
        $data['title']   = $product->first()->title;
        $data['qty']     = $product->count();
        $data['prix']    = $product->first()->price_normal.' CHF';
        $data['special'] = $product->first()->price_special ? $product->first()->price_special.' CHF' : '';
        $data['free']    = $product->first()->pivot->isFree ? 'Oui' : '';
        $data['rabais']  = $product->first()->pivot->rabais ? ceil($product->first()->pivot->rabais).'%' : '';

        $converted[] = $info + $data + $user;
    }


    echo '<pre>';
    print_r($converted);
    echo '</pre>';exit();

    echo '<pre>';
    print_r($grouped);
    echo '</pre>';*/
    $colloques    = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
    $Inscriptions = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
   // $liste  =  $Inscriptions->getByColloque(39);
    $inscription  =  $Inscriptions->getMultiple([12607]);

    
    echo '<pre>';
    print_r($inscription->first()->list_rappel);
    echo '</pre>';
    
    exit();


    $colloque  =  $colloques->find(39);

    $user_options = $inscription->user_options->map(function ($option, $key) {
        if($option->groupe_id){
            return 'choix-'.$option->groupe_id;
        }
        return 'check-'.$option->id;
    })->implode(',');

    $grouped = $liste->groupBy(function ($inscription, $key) {
        return $inscription->user_options->map(function ($option, $key) {
            if($option->groupe_id){
                return 'choix-'.$option->groupe_id;
            }
            return 'check-'.$option->id;
        })->implode(',');
    })->toArray();

    $options = $colloque->options->map(function ($item, $key) {
        if( !$item->groupe->isEmpty() ){
            return $item->groupe->map(function ($groupe, $key) {
                return 'choix-'.$groupe->id;
            });
        }
        return 'checkbox-'.$item->id;
    });

    echo '<pre>';
    print_r($grouped);
    print_r($user_options);
    echo '</pre>';exit();

});

Route::get('sondage', function()
{
    $model = \App::make('App\Droit\Sondage\Repo\SondageInterface');

    $sondage = $model->find(1);

    $max = $sondage->questions->max('pivot.rang');

    $exist = $sondage->questions->contains('id',1);

/*    $sondages = $model->getAll();

    $sorting = [2,1,3,5,4];
    $id = 23;

    $multiplied = collect($sorting)->map(function($item,$key) {
        return ['key' => $key, 'id' => $item];
    })->mapWithKeys(function($item) {
        return [['key' => $item['id'], 'rang' => $item['key']]];
    })->keyBy('key')->map(function ($item, $key) {
        unset($item['key']);
        return $item;
    });*/


    echo '<pre>';
    print_r($exist);
    echo '</pre>';exit();
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
    //$abos  = $model->allByAdresse(4983);

    $inscription = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
    $rappels     = \App::make('App\Droit\Inscription\Repo\RappelInterface');
    $rappel     = $rappels->find(1);

    //$job = new App\Jobs\NotifyAdminNewAbo($abos);
    $job = new App\Jobs\SendRappelEmail($rappel);

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

    $inscrit     = $inscription->find(7913);
    //$group       = $groups->find(3);

    $data = [
        'title'        => $title,
        'concerne'     => '<span style="color:#9e0b0f;">Rappel</span>',
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

    //return View::make('emails.colloque.confirmation', $data);
    return View::make('emails.colloque.rappel', $data);

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

    $model = new \App\Droit\Email\Entities\Email();

    $email = $model->find(4);
    echo '<pre>';
    print_r($email->body_clean);
    echo '</pre>';exit;

});

Route::get('factory', function()
{
    $make = new \tests\factories\ObjectFactory();
    $products = $make->product(15);
    $colloque = $make->colloque();
    //$orders = $make->order(5);
    //$membre = $make->items('Member', 1);

    //$payed   = $make->order(5);
    //$pending = $make->order(3);

    // set 5 to payed status
    //$resu = $make->updateOrder($payed, ['column' => 'payed_at', 'date' => '2016-09-10']);

    echo '<pre>';
    //print_r($membre);
    echo '</pre>';

});

Route::get('merge', function () {

      // Export adresses
/*    $exporter = new \App\Droit\Generate\Export\ExportAdresse();
    $exporter->merge();*/

    $worker = \App::make('App\Droit\Abo\Worker\AboWorkerInterface');

    // Directory for edition => product_id
    $dir       = 'files/abos/facture/273';
    $reference = 'RJN';
    $edition   = '2014';
    $name      = 'facture_'.$reference.'_'.$edition;
    // Get all files in directory
    $files = \File::files(public_path($dir));

    if(!empty($files))
    {
        $worker->merge($files, $name, 1);
    }
    
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
