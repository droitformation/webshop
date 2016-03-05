<?php
/* ============================================
 * Test routes
 ============================================ */

Route::get('cartworker', function()
{

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

/*    $order = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
    $item  = $order->find(2908);

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
    $inscription   = $inscriptions->find(9524);
    $groupe        = $groupes->find(1);


    $abofacture    = $abofactures->find(939);
    $abouser   = $abousers->find(531);

    $generator     = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');

    //$job = new \App\Jobs\MakeDocument($inscription);
    //$job->handle();
    $generator->stream = true;

    //$generate = new \App\Droit\Generate\Entities\Generate($abofacture);

   return $generator->makeAbo('facture', $abofacture ,2);

    echo '<pre>';
    print_r($abouser);
    echo '</pre>';

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
    $inscrit     = $inscription->find(9524);

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

Route::get('dispatch', function()
{
    $document    = 'analyse';
    $interface   = ucfirst($document);
    $directorie  = 'arrets';
    $putinfolder = $document.'s';

    $model  =  App::make('App\Droit\\'.$interface.'\\Repo\\'.$interface.'Interface');

    $models = $model->getAll(2);
    $files  = $models->lists('file');

    foreach($files as $path)
    {
        $file = explode('/', $path);
        $file = end($file);

        $tosearch[] = $file;
    }

    $path   = public_path('files').'/'.$directorie.'/dispatch';
    $search = File::allFiles($path);

    foreach($search as $find)
    {
        $file = explode('/', $find);
        $file = end($file);

        if(in_array($file,$tosearch) && File::exists($find) && File::isFile($find))
        {
            $target = public_path('files').'/'.$putinfolder.'/bail/'.$file;

            //File::copy( $find, $target );
            echo $find;
            echo '<br/>';
        }
    }

    echo '<pre>';
    //print_r($tosearch);
    echo '</pre>';

    exit;

});

Route::get('dispatchnewsletter', function()
{
    $model  =  App::make('App\Droit\Newsletter\Repo\NewsletterContentInterface');

    $models = $model->getAll();
    $files  = $models->lists('image')->all();
    $files = array_filter($files);

    foreach($files as $path)
    {
        $file = explode(',', $path);

        foreach($file as $item){
            $tosearch[] = $item;
        }
    }

    $path   = public_path('dispatch/newsletter_images');
    $search = File::allFiles($path);

    foreach($search as $find)
    {
        $file = explode('/', $find);
        $file = end($file);
        echo $file;
        echo '<br/>';

        if(in_array($file,$tosearch) && File::exists($find) && File::isFile($find))
        {
            $target = public_path('files').'/uploads/'.$file;
            File::copy( $find, $target );
        }
    }

    echo '<pre>';
    //print_r($files);
    echo '</pre>';

    exit;

});

Route::get('dispatchcolloque', function()
{
    $model  =  App::make('App\Droit\Colloque\Repo\ColloqueInterface');

    $models = $model->getAll();

    foreach($models as $colloque)
    {
        $files = $colloque->documents->lists('path','type')->all();

        foreach($files as $type => $item)
        {
            $tosearch[$colloque->id] = $files;
        }
    }

    $path   = public_path('files/colloques/temp');
    $search = File::allFiles($path);

    foreach($search as $file)
    {
        $file = explode('/', $file);
        $file = end($file);
        $found[] = $file;
    }

    foreach($tosearch as $coloque)
    {
        foreach($coloque as $type => $item)
        {
           // echo $item.'<br/>';

            if(in_array($item,$found) && File::exists(public_path('files/colloques/temp/'.$item)) && File::isFile(public_path('files/colloques/temp/'.$item)))
            {
                $target = public_path('files').'/colloques/'.$type.'/'.$item;
                echo $target;
                echo '<br/>';
                File::copy( public_path('files/colloques/temp/'.$item), $target );
            }
        }
    }

    echo '<pre>';
   // print_r($found);
    echo '</pre>';

    exit;

});

Route::get('duplicates', function () {

    $model   = new \App\Droit\Adresse\Entities\Adresse();
    $results = $model->select('email')->groupBy('email')->havingRaw('count(*) > 1')->get();

    echo '<pre>';
    print_r($results);
    echo '</pre>';exit;
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

