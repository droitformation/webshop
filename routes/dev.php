<?php
/* ============================================
 * Test routes
 ============================================ */

Route::get('abos_test', function () {

    $abo       = \App::make('App\Droit\Abo\Repo\AboUserInterface');
    $factures  = \App::make('App\Droit\Abo\Repo\AboFactureInterface');
    $rappels  = \App::make('App\Droit\Abo\Repo\AboRappelInterface');
    $rappel  = $rappels->find(462);
    echo '<pre>';
    print_r($rappel->doc_rappel);
    echo '</pre>';exit();
  /*  $all = $abo->getAll()->where('abo_id',2);

    $all->map(function ($abo, $key) {
        if(isset($abo->user->user)){
            $abo->user_id = $abo->user->user->id;
        }

        if(isset($abo->tiers->user)){
            $abo->tiers_user_id = $abo->tiers->user->id;
        }

        $abo->save();

        echo '<pre>';
        print_r($abo->toArray());
        echo '</pre>';
    });*/

    /*
        list($hasUser, $noUser) = $all->partition(function ($abo_user) {
            return isset($abo_user->user->user);
        });*/
/*
    $image = new Imagick($tmpfname);
    $ident = $image->identifyImage();

    echo '<pre>';
    print_r($ident);
    echo '</pre>';exit();*/

/*    echo '<pre>';
    echo 'has user <br/>';
    print_r($hasUser->count());
    echo '<br/>no user <br/>';
    print_r($noUser->count());
    echo '</pre>';exit();*/

});

Route::get('resize', function () {

    $upload = \App::make('App\Droit\Service\UploadInterface');

    $file = 'files/products/2012_Couv_ProtectAdultes.jpg';
    $destination = 'files/products/thumbs/2012_Couv_ProtectAdultes.jpg';

    $files = \File::files('files/uploads');

    $main_path = 'files/uploads/';
    $thumb_path = 'files/uploads/thumbs/';

    $allowed = ['jpg','jpeg','png','gif','PNG','JPEG','JPG','GIF'];

    $paths = collect($files)->map(function ($file, $key) use ($allowed) {

        $mime      = \File::mimeType($file);
        $extension = \File::extension($file);

        if(substr($mime, 0, 5) == 'image' && in_array($extension,$allowed))
        {
            $file = explode('/', $file);
            $file = end($file);

            return $file;
        }

        return false;
    })->filter(function ($item, $key) {
        return $item ? $item : false;
    });

   /* echo '<pre>';
    print_r($paths);
    echo '</pre>';exit();*/

    $chunks = $paths->chunk(50);

    foreach($chunks as $chunk){
        $chunk->each(function ($path, $key) use ($main_path, $thumb_path, $upload) {

            $file        = $main_path.$path;
            $destination = $thumb_path.$path;

            $upload->resize( $file , $destination ,120 , 100);

        });
    }

    echo '<pre>';
    print_r($paths);
    echo '</pre>';exit();

});

Route::get('mapped', function () {

    // PI2 = 54
    // Ruedin 26
    // kraus 29
    $model = \App::make('App\Droit\Adresse\Repo\AdresseInterface');
    $adresses_ruedin = $model->getBySpecialisations([26]);
    $adresses_kraus = $model->getBySpecialisations([29]);

    $change = collect([]);
    $change = $change->merge($adresses_ruedin);
    $change = $change->merge($adresses_kraus);

    $change = $change->unique('id');


    $adressespi2 = $model->getBySpecialisations([54]);

    echo '<pre>';
    print_r($adressespi2->count());
    echo '</pre>';exit();

    foreach($change as $adresse){
        $lists = $adresse->specialisations->pluck('title','id')->all();
     /*   unset($lists[26]);
        unset($lists[29]);
        $lists[54] = '[PI]2';
        $lists = array_unique($lists);

        echo '<pre>';
        print_r(array_keys($lists));
        echo '</pre>';*/

        //$adresse->specialisations()->sync(array_keys($lists));
    }

    exit();

});


Route::get('testing', function() {

    //$mailjet = \App::make('App\Droit\Newsletter\Service\Mailjet');
    $groups       = \App::make('App\Droit\Inscription\Repo\GroupeInterface');
    $generator    = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
    $colloques    = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
    $model_inscriptions  = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
    $users        = \App::make('App\Droit\User\Repo\UserInterface');
    $adresses    = \App::make('App\Droit\Adresse\Repo\AdresseInterface');
    $abos        = \App::make('App\Droit\Abo\Repo\AboInterface');
    $factures    = \App::make('App\Droit\Abo\Repo\AboFactureInterface');
    $prices      = \App::make('App\Droit\Price\Repo\PriceInterface');
    $products    = \App::make('App\Droit\Shop\Product\Repo\ProductInterface');
    $newslist    = \App::make('App\Droit\Newsletter\Repo\NewsletterListInterface');
    $rappels       = \App::make('App\Droit\Inscription\Repo\RappelInterface');
    $generator   = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');

    $worker       = \App::make('App\Droit\Inscription\Worker\RappelWorkerInterface');

    $orders  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
    $model = new \App\Droit\User\Entities\User();

    $nouveautes = $products->getByCategorie('Nouveautés');

    $model = App::make('App\Droit\Categorie\Repo\CategorieInterface');
    $categorie = $model->find(92);
    $references = $categorie->arrets->map(function ($item, $key) {
        return [
            'id' => $item->id,
            'reference' => $item->reference
        ];
    })->sortBy('reference')->values();

    echo '<pre>';
    print_r($references);
    echo '</pre>';exit();

/*    foreach (range(0, 9900, 500) as $i) {
        $users = $model->with(['adresses'])->offset($i)->offset($i)->limit(500)->get();

        foreach ($users as $user){
            $adresse = $user->adresse_livraison;
            if(empty($adresse)){
                echo $user->name. ' | user id: '.$user->id;
                echo '<br/>';
            }
        }
    }*/

    $sixmonthago = \Carbon\Carbon::today()->subMonths(6)->toDateTimeString();
    echo '<pre>';
    print_r($nouveautes->toArray());
    echo '</pre>';exit();
    exit();

    $me      = $users->find(710);
    //$inscription = $inscriptions->find(14892);

    //$inscriptions = $model_inscriptions->getMultiple([14861,14870,14902]);

    //$worker->generateWithBv($inscriptions);

    $adresses = $me->adresses->where('type',1);
    echo '<pre>';
    print_r($adresses);
    echo '</pre>';
    exit();

    //$import_worker = \App::make('App\Droit\Newsletter\Worker\ImportWorkerInterface');

   // $model = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');

   // $inscription = $model->find(14825);

    //$generate = new \App\Droit\Generate\Entities\Generate($inscription);

    //$bon = $generate->getFilename('bon', 'bon');
    //$bv  = $generate->getFilename('bv', 'bv');

    //$docs = [$inscription->doc_bv,$inscription->doc_facture];
/*
    collect(array_filter($docs))->map(function ($doc, $key) {

        $filename = explode('/', $doc);
        $filename = end($filename);

        \File::move($doc, public_path('files/colloques/bak/'.$filename));
    });*/

 /*   collect(array_filter($docs))->map(function ($doc, $key) {
        \File::move($doc, public_path('files/colloques/bak/'.basename($doc)));
    });*/

exit();
   // File::move($old_path, $new_path);

/*
    $inscriptions = $model->getColloqe(113, $filters = ['status' => 'free'],false);


    echo '<pre>';
    print_r($inscriptions->count());
    print_r($inscriptions->toArray());
    echo '</pre>';exit();*/

    /*
    $user = new \App\Droit\User\Entities\User();
    $dup  = $user->with(['adresses'])->get();

    $multiplied = $dup->reject(function ($item, $key) {
        return $item->adresses->contains('type',1);
    });

   $multiplied = $dup->reject(function ($item, $key) {
        return !$item->adresses->contains('type',2);
    })->groupBy('user_id');

    echo '<pre>';
    print_r($multiplied->toArray());
    echo '</pre>';exit();
*/
   // $occurrences  = \App::make('App\Droit\Occurrence\Repo\OccurrenceInterface');
    //$occurrence   = $occurrences->find(1);

   // $colloques  = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
   // $colloque   = $colloques->find(107);
    //$user = new \App\Droit\User\Entities\User();
   // $dup  = $user->with(['adresses'])->get();
    //$dup = $user->with(['adresses'])->get();


/*    $multiplied = $dup->reject(function ($item, $key) {
        return $item->adresses->count() == 1 && $item->adresses->contains('type',1);
    })->reject(function ($item, $key) {
        return $item->adresses->count() > 1 && $item->adresses->contains('type',1);
    });

    echo '<pre>';
    print_r($multiplied->toArray());
    echo '</pre>';exit();*/

    $model  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
    $orders = $model->getPeriod(['period' => ['start' => '2016-01-01', 'end' => '2016-12-31']])->where('admin',null);

    $orders = $orders->map(function ($order, $key) {

        if(!$order->products->isEmpty()){
            $worker = \App::make('App\Droit\Shop\Order\Worker\OrderMakerInterface');
            $data = $worker->updateOrder($order, $order->shipping_id);

            //$order->amount = $data['amount'];
            //$order->save();
            return $data + ['order_no' => $order->order_no, 'old_amount' => $order->amount, 'amount' => $data['amount']];
        }

        return ['order_no' => $order->order_no, 'old_amount' => $order->amount, 'amount' => $order->amount];
    });

    echo '<pre>';
    print_r($orders);
    echo '</pre>';exit();

   // $price  = $colloque->prices->first();
    //$option = $colloque->options->first();
    
    //$price  = $prices->find(388);

    //$years     = $colloques->getYears();


    //$make  = new \tests\factories\ObjectFactory();
   // $make->makeInscriptions(1, 1);

    
    //app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);
    //$years = array_keys($years->toArray());


    /*    $rappel_model    = \App::make('App\Droit\Shop\Rappel\Repo\RappelInterface');
        $model  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
        $orders = $model->getLast(1);
        $rappel = $rappel_model->find(27);
        $order  = $orders->find(3263);

        $generator->stream = true;
        $generator->setMsg(['warning' => 'Après vérification de notre comptabilité, nous nous apercevons que la facture concernant la commande susmentionnée est due.']);

        return $generator->factureOrder($order,$rappel);
    */
/*
    $colloque = $colloques->find(39);
    $adresse  = $adresses->find(6005);


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
   // $colloques    = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
   // $Inscriptions = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
   // $liste  =  $Inscriptions->getByColloque(39);
   // $inscription  =  $Inscriptions->getMultiple([12607]);


    $colloque  =  $colloques->find(120);

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

Route::get('sondage_test', function()
{
    $model = \App::make('App\Droit\Sondage\Repo\SondageInterface');

    $sondages = $model->getAll();
    $sondage =  $sondages->first();

    echo '<pre>';
    print_r($sondage);
    echo '</pre>';exit();


});

Route::get('abo1', function()
{
    $abo       = \App::make('App\Droit\Abo\Repo\AboUserInterface');
    $factures  = \App::make('App\Droit\Abo\Repo\AboFactureInterface');
    $facture   = $factures->find(1581);//701

    $generator  = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');

    $generator->stream = true;
    //$generator->setMsg(['warning' => 'Après vérification de notre comptabilité, nous nous apercevons que la facture concernant la commande susmentionnée est due.']);

    //return $generator->makeAbo('facture', $facture);
    return $generator->makeAbo('facture', $facture,  null,  null);
});

Route::get('abo2', function()
{
    $abo       = \App::make('App\Droit\Abo\Repo\AboUserInterface');
    $factures  = \App::make('App\Droit\Abo\Repo\AboFactureInterface');
    $facture = $factures->find(381);//1697
    $generator  = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');

    $generator->stream = true;
    //$generator->setMsg(['warning' => 'Après vérification de notre comptabilité, nous nous apercevons que la facture concernant la commande susmentionnée est due.']);

    return $generator->makeAbo('facture', $facture);
});

Route::get('cartworker', function()
{

    $user   = Auth::user()->load('adresses');
    $worker = \App::make('App\Droit\Shop\Cart\Worker\CartWorker');
    //$worker = \App::make('App\Droit\Abo\Worker\AboWorker');

    $specialisations = \App::make('App\Droit\Specialisation\Repo\SpecialisationInterface');
    $specialisation  = $specialisations->search('Bai',true);

    if(!$specialisation->isEmpty())
    {
        foreach($specialisation as $result)
        {
            $data[] = $result->title;
        }
    }

    $data = $specialisation->map(function ($item, $key) {
        return $item->title;
    })->all();


    $abo        = \App::make('App\Droit\Abo\Repo\AboUserInterface');
    $factures  = \App::make('App\Droit\Abo\Repo\AboFactureInterface');
    $facture = $factures->find(2);//1697


    //$generator->setMsg(['warning' => 'Après vérification de notre comptabilité, nous nous apercevons que la facture concernant la commande susmentionnée est due.']);

    //return $generator->factureOrder($order,$rappel);

   // return $generator->makeAbo('facture', $facture);



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

/*
*/

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

    $groupes       = new \App\Droit\Inscription\Entities\Groupe();


    /*$range = ['a','b','c','d'];

    $inscriptions = $inscriptions->map(function ($inscription) {

        if(!is_numeric($inscription->name_inscription)) {
            $name = explode(' ', $inscription->name_inscription);
            $name = end($name);
        }
        else{
            $name = $inscription->name_inscription;
        }

        return ['name' => $inscription->name_inscription, 'last_name' => str_slug($name)];
    });

    $inscriptions = $inscriptions->filter(function ($inscription, $key) use ($range) {
        $first = $inscription['last_name'][0];
        return in_array($first, $range);
    });

    echo '<pre>';
    print_r($inscriptions);
    echo '</pre>';exit();*/


/*    $groupe        = $groupes->find(1);


    $abofacture    = $abofactures->find(939);
    $abouser   = $abousers->find(531);*/


    //$job = new \App\Jobs\MakeDocument($inscription);
    //$job->handle();
    //$generator->stream = true;

    //$generate = new \App\Droit\Generate\Entities\Generate($abofacture);

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

Route::get('categoriestest', function() {
    //$model = App::make('App\Droit\Arret\Repo\ArretInterface');
   // $modela = App::make('App\Droit\Analyse\Repo\AnalyseInterface');
   // $pages = App::make('App\Droit\Page\Repo\PageInterface');

    $model  = \App::make('App\Droit\User\Repo\UserInterface');
    $user = $model->find(710);

    $adresses = $user->adresses->map(function ($item) use ($current) {
        return [$item->id => $item->type];
    });

    echo '<pre>';
    print_r($adresses);
    echo '</pre>';exit();

    // Create colloque
/*    $make     = new \tests\factories\ObjectFactory();
    $colloque = $make->colloque();
    $person   = $make->makeUser();

    $prices   = $colloque->prices->pluck('id')->all();
    $options  = $colloque->options->pluck('id')->all();

    $data = [
        //'type'        => 'multiple',
        //'colloque_id' => $colloque->id ,
        // 'user_id'     => $person->id,
        'participant' => [
            'Cindy Leschaud',
            'Coralie Ahmetaj'
        ],
        'price_id' => [
            $prices[0],
            $prices[0]
        ],
        'occurrences' => [
            [2],
            [2,3]
        ],
        'options' => [
            0 => [
                $options[0],
                [148 => 'psum odolr amet']
            ],
            1 => [
                $options[0], [148 => 'lorexm ipsu']
            ]
        ],
        'groupes' => [
            [147 => 44],
            [147 => 45]
        ]
    ];

    $contacts = collect($data)->transpose()->map(function ($register) {
        return [
            'participant' => $register[0],
            'price_id' => $register[1],
            'occurrences' => $register[2],
            'options' => $register[3],
            'groupes' => $register[4],
        ];
    })->toArray();*/


/*    $arrets = $results->map(function ($arret, $key) {
        return [
            'id'         => $arret->id,
            'title'      => $arret->reference.' '.$arret->pub_date->formatLocalized('%d %B %Y'),
            'reference'  => $arret->reference,
            'abstract'   => $arret->abstract,
            'pub_text'   => $arret->pub_text,
            'document'   => $arret->document ? asset('files/arrets/'.$arret->file) : null,
            'categories' => !$arret->categories->isEmpty() ? $arret->categories : null,
        ];
    });

    $result = [
        'arrets' => $arrets,
        'pagination' => [
            'total'    => $results->total(),
            'per_page' => $results->perPage(),
            'current_page' => $results->currentPage(),
            'last_page'    => $results->lastPage(),
            'from' => $results->firstItem(),
            'to'   => $results->lastItem()
        ],
    ];*/

/*    $analyses = $modela->allForSite(3, null);

    $analyses = $analyses->map(function ($analyse, $key) {

        if(!$analyse->arrets->isEmpty()) {
            $references = $analyse->arrets->map(function ($item, $key) {
                return '<a href="#'.$item->reference.'">'.$item->reference.' du '.$item->pub_date->formatLocalized('%d %B %Y').'</a>';
            });
        }

        return [
            'id'         => $analyse->id,
            'references' => isset($references) && !$references->isEmpty() ? $references : null,
            'auteurs'    => $analyse->authors->implode('name', ', '),
            'abstract'   => $analyse->abstract,
            'document'   => $analyse->document ? asset('files/analyses/'.$analyse->file) : null,
        ];
    });*/


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

Route::get('test_transpose', function()
{

    $data = [
        'user_id' => 710,
        'adresse' => [
            'civilite_id' => 2,
            'company'     => 'Unine',
            'first_name'  => 'Jane',
            'last_name'   => 'Doe',
            'email'       => 'jane.doe@domain.ch',
            'password'    => '1235',
            'adresse'     => 'Rue du Marché_neuf 14',
            'complement'  => '',
            'cp'          => '',
            'npa'         => '2502',
            'ville'       => 'Bienne',
            'canton_id'   => 6,
            'pays_id'     => 208,
        ],
        'order' => [
            'products' => [
                0 => 233, 1 => 309
            ],
            'qty' => [
                0 => 1, 1 => 2
            ],
            'rabais' => [
                null,
                null
            ],
            'price'  => [
                null,
                10
            ]
        ]
    ];

    $preview  = new App\Droit\Shop\Order\Entities\OrderPreview($data);
    $prepared = $preview->adresse();

    echo '<pre>';
    print_r($prepared);
    echo '</pre>';exit();

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
        $m->from('info@publications-droit.ch', 'Admin');
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

Route::get('extract_stats', function()
{
    $model  = new \App\Droit\Inscription\Entities\Inscription();

    $inscriptions = $model->take(1)->get();

    $all = [];

    foreach($inscriptions as $inscription){
        $data = $inscription->toArray();

        $data['canton_id'] = isset($inscription->inscrit) && isset($inscription->inscrit->adresse_contact) ? $inscription->inscrit->adresse_contact->canton_id : '';
        $data['profession_id'] = isset($inscription->inscrit) && isset($inscription->inscrit->adresse_contact) ? $inscription->inscrit->adresse_contact->profession_id : '';
        $data['civilite_id'] = isset($inscription->inscrit) && isset($inscription->inscrit->adresse_contact)  ? $inscription->inscrit->adresse_contact->civilite_id : '';

        $data['price'] = isset($inscription->price) ? $inscription->price->price_cents : '';

        unset($data['deleted_at']);
        unset($data['price_id']);
        unset($data['user_id']);
        unset($data['group_id']);

        $all[] = $data;
    }

  /*  \Excel::create('Export inscriptions', function ($excel) use ($all) {
        $excel->sheet('Export', function ($sheet) use ($all) {
            $sheet->setOrientation('landscape');
            $sheet->appendRow(array_keys($all[0]));
            $sheet->rows($all);
        });

    })->export('csv');*/

    echo '<pre>';
    print_r(implode(',',array_keys($all[0])));
    echo '</pre>';exit();

});

Route::get('sondage', function()
{
    $sondages = new App\Droit\Sondage\Entities\Sondage();
    $sondage  = $sondages->find(5);

    $rep_model = new App\Droit\Sondage\Entities\Sondage_reponse();
    $find = $rep_model->find('15');

    $avis = $sondage->avis->pluck('question','id');

    $reponses = $sondage->reponses->map(function ($item, $key) {
        return $item->items;
    })->flatten();

    $rep = $reponses->where('avis_id',13);

/*   echo '<pre>';
    print_r($rep->groupBy('reponse'));
    echo '</pre>';exit();*/

    $multiplied = $sondage->avis->mapWithKeys(function ($item, $key) use ($sondage, $reponses) {

        $rep = $reponses->where('avis_id', $item->id);

        if($item->type == 'radio' || $item->type == 'checkbox'){
            $answers = $rep->groupBy('reponse')->map(function ($av, $key) use ($item) {
                return $av->count();
            });
        }
        else{
            $answers = $item->type == 'chapitre' ? '' : $rep->pluck('reponse');
        }

        return [
            $item->id => [
                'title'    => $item->question,
                'reponses' =>  $item->type != 'chapitre' ?  $answers : null
            ]
        ];
    });

    echo '<pre>';
    print_r($multiplied);
    echo '</pre>';exit();

    $reg = $sondage->reponses->map(function ($item, $key) {
        return $item->items;
    })->flatten()
        ->reject(function ($item, $key) {
            $item->load('avis');
            return !isset($item->avis);
        })
        ->groupBy('avis_id')
        ->mapWithKeys(function ($item, $key) {

            if($item->first()->avis->type == 'radio' || $item->first()->avis->type == 'checkbox'){
                $reponses = $item->groupBy('reponse')->map(function ($av, $key) use ($item) {
                    return $av->count();
                });
            }
            else{
                $reponses = $item->first()->avis->type == 'chapitre' ? '' : $item->pluck('reponse');
            }

            $title = $item->first()->avis->type == 'chapitre' ? ['chapitre' => $item->first()->avis->question] : ['title' => $item->first()->avis->question];

            return [$item->first()->avis_id => $title +  ['reponses' => $reponses, 'type' => $item->first()->avis->type]];

        })->toArray();

   // $reg = sortArrayByArray($reg, $avis->keys('id')->all());

    echo '<pre>';
    print_r($reg);
    echo '</pre>';

   // app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);

    //return View::make('emails.sondage', $data);
});

Route::get('testproduct', function()
{
    $reminders  = \App::make('App\Droit\Reminder\Repo\ReminderInterface');
    $list = $reminders->toSend();

    $insc_repo  = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
    $group_repo = \App::make('App\Droit\Inscription\Repo\GroupeInterface');

    $inscription = $insc_repo->find(16071);
    $groupe = $group_repo->find(32);

    echo '<pre>';
    print_r($inscription->documents);
    print_r($groupe->documents);
    echo '</pre>';exit();


    foreach($list as $reminder)
    {
        $model = new $reminder->model;

        if ($model instanceof Illuminate\Database\Eloquent\Model)
        {
           // $model_id = $reminder->model_id;
           // $item     = $model->find($model_id);

      /*      echo '<pre>';
            print_r($item);
            echo '</pre>';exit;*/

           // return View::make('emails.reminder', ['reminder' => $reminder, 'item' => $item]);
            //exit;
        }
    }

});

Route::get('manager', function()
{

    echo '<pre>';
    print_r(array_keys(config('sites')));
    echo '</pre>';exit();
    $manager = App::make('App\Droit\Service\FileWorkerInterface');
    $files   = $manager->dir_contains_children('files/pictos');

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

Route::get('/test_mailgun', function () {

    $worker  = \App::make('App\Droit\Newsletter\Worker\CampagneInterface');
    $mailgun = \App::make('App\Droit\Newsletter\Worker\MailgunInterface');

    $toSend = \Carbon\Carbon::now()->addMinutes(1)->toRfc2822String();
    $html    = $worker->html(1665);

    $mailgun->setSender('info@publications-droit.ch', 'Publications-droit')
        ->setHtml($html)
        ->setSendDate($toSend)
        ->setTags(['testing_123'])
        ->setRecipients(['cindy.leschaud@gmail.com','cindy.leschaud@unine.ch']);

    $date     = '2017-11-21';
    $tag      = 'campagne_1669';
    $response = $mailgun->getStats($date,$tag);

    $results = $mailgun->mailgun_agregate($response);
/*
    $tracking = App::make('App\Droit\Newsletter\Repo\NewsletterTrackingInterface');
    $tracking->logSent([
        'campagne_id'    => 1,
        'send_at'        => \Carbon\Carbon::now()->toDateTimeString(),
        'list_id'        => 12,
    ]);*/

    echo '<pre>';
    print_r($response);
    echo '</pre>';
   // $response = $mailgun->sendTransactional('testing');

    echo '<pre>';
    print_r($results);
    echo '</pre>';exit();
});

Route::get('cleanlist', function()
{
    //$clean = new \App\Droit\Newsletter\Worker\CleanWorker(1588258,1,'pubdroit');
    //$clean = new \App\Droit\Newsletter\Worker\CleanWorker(1588350,2,'bail');
    $clean = new \App\Droit\Newsletter\Worker\CleanWorker(1588260,3,'matrimonial');
    $clean->save();

    // Filter pubdroit => mailjet
    //$subscribers = $clean->filter();
    //$clean->setMissing();

    // Filter Mailjet DB
    $subscribers = $clean->missingDB();
   // $clean->addSubscriber($subscribers,1);/
    //
     $clean->clean();

    echo '<pre>';
    //print_r($clean->subscribers);
    print_r($subscribers);
    //print_r(implode('<br>',$clean->subscribers['ok']));
    echo '</pre>';exit();
});

Route::get('factory', function()
{

    /*
        $model  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
        $product  = factory(App\Droit\Shop\Product\Entities\Product::class)->make(['weight' => 10000, 'price'  => 1000]);

        $order = [
            'user_id' => 710,
            'order'   => [
                'products' => [0 => 11, 1 => 12],
                'qty'      => [0 => 2, 1 => 2],
                'rabais'   => [0 => 25],
                'gratuit'  => [1 => 1]
         ],
            'admin' => 1
        ];

    /*    $order = $model->find(3891);

        $paquets = collect($order->paquets)->groupBy(function ($item, $key) {
            return ($item->shipping->value/1000).' Kg | '.$item->shipping->price_cents;
        })->map(function ($item, $key) {
            return $item->sum('qty');
        });

        echo '<pre>';
        print_r($paquets);*/

    $worker = \App::make('App\Droit\Newsletter\Worker\SubscriptionWorkerInterface');
    $subscribe = \App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface');
    $newsletter = \App::make('App\Droit\Newsletter\Repo\NewsletterInterface');

    $mailjet =  \App::make('App\Droit\Newsletter\Worker\MailjetServiceInterface');

    $mailjet->setList(1588258);
    $pubdroit = $newsletter->find(1);

    $allusersDB = $pubdroit->subscriptions->unique('email');

    $subscribers = [];

    /*
        foreach (range(0, 8000, 1000) as $i) {
            $users = $mailjet->getSubscribers($i);
            $allusers[] = collect($users)->map(function ($item, $key) {
                return $item['Email'];
            });
        }

        $emails = array_flatten($allusers);

        $data = collect($emails)->map(function ($item, $key) {return [$item];});

        Excel::create('pubdroit', function($excel) use ($data) {
            $excel->sheet('Sheetname', function($sheet) use ($data) {
              $sheet->fromArray($data);
            });
        })->store('xls', storage_path('excel'));

        exit;
              */

    $results = \Excel::load(storage_path('excel/pubdroit.xls'), function($reader) {
        $reader->ignoreEmpty();
    })->get();

    $emails = $results->flatten()->all();

    foreach ($allusersDB as $item){
        if(!in_array(strtolower($item->email),$emails)) {
            // if not in abos and confirmed => subscribe
            if($item->activated_at){
                $subscribers['missing'][] = $item->email;
            }
            // if not in abos and not confirmed => delete
            if(!$item->activated_at){
                $subscribers['unconfirmed'][] = $item;
            }
        }
        else{
            $subscribers['ok'][] = $item->email;
        }
    }

    echo '<pre>';
    print_r($subscribers);
    echo '</pre>';exit();

/*    foreach ($subscribers['missing'] as $missing){
        echo $missing.'<br/>';

        $mailjet->subscribeEmailToList($missing);
    }*/

    foreach ($subscribers['unconfirmed'] as $missing){
        $missing->delete();
    }

    exit;

/*    $results = \Excel::load('public/files/import/pi2.xlsx', function($reader) {
        $reader->ignoreEmpty();
        $reader->setSeparator('\r\n');
    })->get();

    $results = $results->pluck('email');

    foreach($results as $email){
        $exist = $worker->exist($email);
        if($exist){
            echo '<br/>is in '.$email;
            $exist->subscriptions()->detach(10);
            $exist->subscriptions()->attach(10);
        }
        else{
            echo '<br/>is not in '.$email;
            $worker->make($email,10);
        }
    }

    foreach($results as $email){
        $exist = $worker->exist($email);
        if($exist){
            echo '<br/>is in '.$email;
            $exist->subscriptions()->detach(10);
            $exist->subscriptions()->attach(10);
        }
        else{
            echo '<br/>is not in '.$email;
            $worker->make($email,10);
        }
    }*/
});

Route::get('badges_test', function () {

    $colloques    = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
    $inscriptions  = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');

    $badges = config('badge');
    // Get badge format
    $format = explode('|', 'pdf|zweckform27');
    $badge  = $badges[$format[1]];

    // Get inscriptions names and chunk data for rows per page
    $colloque     = $colloques->find(112);
    $inscriptions = $inscriptions->getByColloqueExport($colloque->id,[]);

    $colloque->load('adresse');

    $exporter = new \App\Droit\Generate\Export\ExportBadge();
    $exporter->setConfig($badge);

    $range = range('h', 'l');
    $exporter->setRange($range);

    ini_set('memory_limit', '-1');

/*    $inscriptions = $inscriptions->map(function ($inscription) {
        if(!is_numeric($inscription->name_inscription)) {
            $name = explode(' ', $inscription->name_inscription);
            $name = count($name) > 2 ? $name[1].' '.$name[2] : end($name);
        }
        else{
            $name = $inscription->name_inscription;
        }
        return ['name' => $inscription->name_inscription, 'last_name' => str_slug($name)];
    })->filter(function ($inscription, $key) use ($range) {
        $first = $inscription['last_name'][0];
        return in_array($first, $range);
    });

    $inscriptions = collect($inscriptions)->sortBy('last_name')->pluck('name')->toArray();*/
    //$results = $exporter->chunkData($inscriptions,3,27);
    //$results = $exporter->good(collect($results));

    return $exporter->export($inscriptions, $colloque);

});

Route::get('/mailable_slide', function () {

    $colloques = \App::make('App\Droit\Colloque\Repo\ColloqueInterface');
    $colloque  = $colloques->find(127);

    $worker = new \App\Droit\Sondage\Worker\SondageWorker();

    $list = $worker->getList(127);

    $texte = 'Lorem ad quîs j\'libéro pharétra vivamus mollis ultricités ut, vulputaté ac pulvinar èst commodo aenanm pharétra cubilia, lacus aenean pharetra des ïd quisquées mauris varius sit. Mie dictumst nûllam çurcus molestié imperdiet vestibulum suspendisse tempor habitant sit pélléntésque sit çunc, primiés ?';
    $url   = secure_url('pubdroit/documents/'.$colloque->id);

    return new App\Mail\SendSlides($colloque,$texte,$url);
});

Route::get('merge', function () {

      // Export adresses
/*    $exporter = new \App\Droit\Generate\Export\ExportAdresse();
    $exporter->merge();*/

    $worker = \App::make('App\Droit\Abo\Worker\AboWorkerInterface');
    $abos   = \App::make('App\Droit\Abo\Repo\AboInterface');
    $products    = \App::make('App\Droit\Shop\Product\Repo\ProductInterface');

    $product = $products->find(347);
    $abo     = $abos->findAboByProduct(347);

    $model = \App::make('App\Droit\Abo\Repo\AboFactureInterface');

    $date = \Carbon\Carbon::parse(date('Y-m-d'))->toDateTimeString();

    $facture = $model->update(['id' => 4501, 'created_at' => $date]);
    \File::delete(public_path($facture->doc_facture));

    echo '<pre>';
    print_r($facture->doc_facture);
    echo '</pre>';exit();
    /*$abonnes = $abo->abonnements->whereIn('status',['abonne','tiers']);

    $grouped = $abonnes->mapToGroups(function ($item, $key) use($product) {
        $dir  = 'files/abos/facture/'.$product->id;
        $path = 'facture_'.$product->reference.'-'.$item->id.'_'.$product->id.'.pdf';
        $filename = public_path($dir.'/'.$path);
        if($item->status == 'tiers'){return ['tiers' => $filename];}
        if($item->exemplaires > 1){return ['multiple' => $filename];}
        return ['abonne' => $filename];
    });

    // Directory for edition => product_id
    $dir   = 'files/abos/facture/'.$product->id;
    // Get all files in directory
    $files = \File::files(public_path($dir));

    $exist = array_intersect($files,$grouped['abonne']->toArray());

    echo '<pre>';
    print_r($exist);
    echo '</pre>';exit();

    $dir   = 'files/abos/facture/347';

    // Get all files in directory
    $files = \File::files(public_path($dir));

    $path = collect($files)->map(function ($file, $key) {
        return basename($file);
    })->filter(function ($value, $key) use ($grouped) {
        return $grouped['tiers']->contains($value);
    });

    echo '<pre>';
    print_r($path);
    echo '</pre>';exit();

    echo '<pre>';
    print_r($grouped['abonne']->count());
    echo '<pre>';
    print_r($grouped['multiple']->count());
    echo '<pre>';
    print_r($grouped['tiers']->count());
    echo '</pre>';exit();*/
    // Directory for edition => product_id
/*    $dir       = 'files/abos/facture/273';
    $reference = 'RJN';
    $edition   = '2014';
    $name      = 'facture_'.$reference.'_'.$edition;
    // Get all files in directory
    $files = \File::files(public_path($dir));

    if(!empty($files))
    {
        $worker->merge($files, $name, 1);
    }*/

    $abonnes = $abo->abonnements->whereIn('status',['abonne','tiers']);
    
});

