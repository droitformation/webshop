<?php namespace tests\factories;

class ObjectFactory
{
    protected $faker;
    protected $professions;
    protected $cantons;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();

        $profession = \App::make('App\Droit\Profession\Repo\ProfessionInterface');
        $canton     = \App::make('App\Droit\Canton\Repo\CantonInterface');

        $this->professions = $profession->getAll()->pluck('title','id')->all();
        $this->cantons     = $canton->getAll()->pluck('title','id')->all();
    }

    public function makeUser($data = [])
    {
        $first_name = isset($data['first_name']) ? $data['first_name'] : $this->faker->firstName;
        $last_name  = isset($data['last_name']) ? $data['last_name'] : $this->faker->lastName;
        $email      = isset($data['email']) ? $data['email'] : $this->faker->email;

        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'email'      => $email,
            'password'   => bcrypt('secret')
        ]);

        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create([
            'civilite_id'   => isset($data['civilite_id']) ? $data['civilite_id'] : $this->faker->numberBetween(1,4),
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'email'         => $email,
            'company'       => $this->faker->company,
            'profession_id' => isset($data['profession']) ? $data['profession'] : 0,
            'telephone'     => '032 690 00 23',
            'mobile'        => '032 690 00 23',
            'fax'           => null,
            'adresse'       => $this->faker->address,
            'npa'           => $this->faker->postcode,
            'ville'         => $this->faker->city,
            'canton_id'     => isset($data['canton']) ? $data['canton'] : 0,
            'pays_id'       => 208,
            'type'         => 1,
            'user_id'      => $user->id,
            'livraison'    => 1
        ]);

        $user->adresses()->save($adresse);

        if(isset($data['specialisations']))
        {
            $user->adresse_contact->specialisations()->attach($data['specialisations']);
        }

        if(isset($data['members']))
        {
            $user->adresse_contact->members()->attach($data['members']);
        }

        return $user->load('adresses');
    }

    public function addMemberships($user, $data)
    {
        if(isset($data['specialisations'])) {
            $user->adresse_contact->specialisations()->attach($data['specialisations']);
        }

        if(isset($data['members'])) {
            $user->adresse_contact->members()->attach($data['members']);
        }

        $user->load('adresses.specialisations','adresses.members');

        return $user;
    }

    public function user($data = [])
    {
        if(count($data) > 1)
        {
            $users = [];

            for ($x = 0; $x < count($data); $x++)
            {
                $users[] = $this->makeUser($data[$x]);
            }

            return collect($users);
        }

        return $this->makeUser();
    }

    public function adresse($user = null)
    {
        return factory(\App\Droit\Adresse\Entities\Adresse::class)->create([
            'civilite_id'   => $this->faker->numberBetween(1,4),
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'email'         => $this->faker->email,
            'company'       => $this->faker->company,
            'profession_id' => isset($data['profession']) ? $data['profession'] : 0,
            'telephone'     => '032 690 00 23',
            'mobile'        => '032 690 00 23',
            'fax'           => null,
            'adresse'       => $this->faker->address,
            'npa'           => $this->faker->postcode,
            'ville'         => $this->faker->city,
            'canton_id'     => isset($data['canton']) ? $data['canton'] : 0,
            'pays_id'       => 208,
            'type'         => 1,
            'user_id'      => isset($user) ? $user->id : null,
            'livraison'    => 1
        ]);
    }

    public function makeAdmin($user)
    {
        $user->roles()->attach(1);

        return $user;
    }

    public function colloque($registration = null, $start = null)
    {
        $registration_at = $registration ? $registration : \Carbon\Carbon::now()->addMonth();
        $start_at        = $start ? $start : \Carbon\Carbon::now()->addMonths(2);

        $compte = factory(\App\Droit\Compte\Entities\Compte::class)->create();

        $colloque = factory(\App\Droit\Colloque\Entities\Colloque::class)->create([
            'titre'           => $this->faker->sentence,
            'soustitre'       => $this->faker->sentence,
            'sujet'           => $this->faker->sentence,
            'remarques'       => $this->faker->text(),
            'start_at'        => $start_at,
            'end_at'          => null,
            'registration_at' => $registration_at,
            'active_at'       => null,
            'organisateur'    => $this->faker->company,
            'location_id'     => 1,
            'compte_id'       => $compte->id,
            'visible'         => 1,
            'bon'             => 1,
            'facture'         => 1,
            'adresse_id'      => 1,
        ]);
        
        // Dependencies
        $price = factory(\App\Droit\Price\Entities\Price::class)->create([
            'colloque_id' => $colloque->id,
            'price'       => 4000,
            'type'        => 'public',
            'description' => 'test',
            'rang'        => 1,
            'remarque'    => 'Prix normal',
        ]);

        $vignette = factory(\App\Droit\Document\Entities\Document::class)->create([
            'colloque_id' => $colloque->id,
            'display'     => 1,
            'type'        => 'illustration',// 'illustration', 'programme', 'document'
            'path'        => 'teamweb.png',
            'titre'       => 'Vignette'
        ]);

        $programme = factory(\App\Droit\Document\Entities\Document::class)->create([
            'colloque_id' => $colloque->id,
            'display'     => 1,
            'type'        => 'programme',// 'illustration', 'programme', 'document'
            'path'        => 'teamweb.pdf',
            'titre'       => 'Programme'
        ]);

        $option = factory(\App\Droit\Option\Entities\Option::class)->create([
            'colloque_id' => $colloque->id,
            'title'       => 'Repas',
            'type'        => 'checkbox',// 'checkbox','choix','text'
        ]);

        $attestation = factory(\App\Droit\Colloque\Entities\Colloque_attestation::class)->create([
            'colloque_id' => $colloque->id,
        ]);

        return $colloque;
    }

    public function colloqueOnlyBon($colloque)
    {
        $colloque->facture  = 0;
        $colloque->compte_id  = null;
        $colloque->save();

        return $colloque;
    }

    public function addAttributesAbo($product)
    {
        $product->attributs()->attach(3,['value' => 'TEST']);
        $product->attributs()->attach(4,['value' => '2017']);

        return $product;
    }

    public function addGroupOption($colloque)
    {
        // Create main option
        $option = factory(\App\Droit\Option\Entities\Option::class)->create([
            'colloque_id' => $colloque->id,
            'title'       => 'Choix conférence',
            'type'        => 'choix',// 'checkbox','choix','text'
        ]);

        factory(\App\Droit\Option\Entities\OptionGroupe::class)->create([
            'colloque_id' => $colloque->id,
            'option_id'   => $option->id,
            'text'        => 'Choix 1',
        ]);

        factory(\App\Droit\Option\Entities\OptionGroupe::class)->create([
            'colloque_id' => $colloque->id,
            'option_id'   => $option->id,
            'text'        => 'Choix 2',
        ]);
    }

    public function product($nbr = null)
    {
        $products = [];
        
        $images = $this->getImages();

        if($nbr)
        {
            for ($x = 0; $x <= $nbr; $x++) 
            {
                $products[] = $this->makeProduct($images);
            }
            
            return collect($products);
        }

        return $this->makeProduct($images);
    }

    public function getImages()
    {
        $images = \File::files(public_path('files/products'));
        
        return collect($images)->map(function ($name) {
            $file = explode('/', $name);
            $file = end($file);

            return $file;
        })->toArray();
    }

    public function makeProduct($images)
    {
        $images = !empty($images) ? $images : ['text.jpg','testing.png'];

        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
            'title'           => $this->faker->sentence,
            'teaser'          => $this->faker->sentence,
            'image'           => $images[array_rand($images)],
            'description'     => $this->faker->text() ,
            'weight'          => 500,
            'sku'             => 10,
            'price'           => $this->faker->numberBetween(10000,30000),
        ]);

        return $product;
    }

    public function order($nbr, $user_id = null)
    {
        $product = new \App\Droit\Shop\Product\Entities\Product();
        $exist   = $product->all();

        if($exist->isEmpty()) {
            $this->product($nbr);
        }

        $orders = [];

        for($x = 1; $x <= $nbr; $x++)
        {
            $products = $product->orderByRaw("RAND()")->take(2)->get();
            $amount   = $products->sum('price');

            $user   = $this->makeUser();
            $person = isset($user_id) ? $user_id : $user->id;

            $order = factory(\App\Droit\Shop\Order\Entities\Order::class)->create([
                'user_id'     => $person,
                'coupon_id'   => null,
                'payement_id' => 1,
                'order_no'    => '2016-0000000'.$x.'',
                'amount'      => $amount,
                'shipping_id' => 1,
                'status' => 'pending',
            ]);

            $order->products()->attach($products->pluck('id')->all());

            $orders[] = $order;
        }

        return collect($orders);
    }

    public function makeAdresseOrder($adresse_id)
    {
        $product = new \App\Droit\Shop\Product\Entities\Product();
        $exist   = $product->all();

        if($exist->isEmpty()) {
            $this->product(4);
        }

        $products = $product->orderByRaw("RAND()")->take(2)->get();
        $amount   = $products->sum('price');

        $order = factory(\App\Droit\Shop\Order\Entities\Order::class)->create([
            'user_id'     => null,
            'adresse_id'  => $adresse_id,
            'coupon_id'   => null,
            'payement_id' => 1,
            'order_no'    => '2016-0000000123',
            'amount'      => $amount,
            'shipping_id' => 1,
        ]);

        $order->products()->attach($products->pluck('id')->all());

        return $order;
    }

    public function updateOrder($orders, $data)
    {
        $commandes = [];

        foreach($orders as $order)
        {
            $name = $data['column']; // payed_at or send_at
            $date = $data['date'];
            
            $order->$name = $date;

            if($name == 'payed_at')
            {
                $order->status = 'payed';
            }

            $order->save();

            $commandes[] = $order;
        }

        return collect($commandes);
    }

    public function items($type, $nbr = 1)
    {
        $specialisations = [];

        if($nbr > 1)
        {
            for ($x = 1; $x <= $nbr; $x++)
            {
                $specialisations[] = $this->makeItems($type);
            }

            return collect($specialisations);
        }

        return $this->makeItems($type);
    }

    public function makeItems($type)
    {
        return factory('App\Droit\\'.$type.'\Entities\\'.$type)->create([
            'title' => $this->faker->jobTitle
        ]);
    }

    public function makeAbo()
    {
        $product = $this->makeProduct($this->getImages());
        $product = $this->addAttributesAbo($product);
        
        $abo =  \App\Droit\Abo\Entities\Abo::create(['title' => 'TestAbo', 'price' => 50, 'plan' => 'year']);
        $abo->products()->attach($product->id);
        
        return $abo;
    }

    public function makeAbonnement($abo = null, $user = null)
    {
        if(!$abo){
            $abo = $this->makeAbo();
        }

        if(!$user){
            $user = $this->makeUser();
        }

        return factory(\App\Droit\Abo\Entities\Abo_users::class)->create([
         'abo_id'         => $abo->id,
         'adresse_id'     => $user->adresses->first()->id,
        ]);
    }

    public function makeUserAbonnement($abo = null, $user = null,$isTiers = false)
    {
        if(!$abo){
            $abo = $this->makeAbo();
        }

        if(!$user){
            $user = $this->makeUser();
        }

        $tiers = $this->makeUser();

        return factory(\App\Droit\Abo\Entities\Abo_users::class)->create([
            'abo_id'   => $abo->id,
            'user_id'  => $user->id,
            'tiers_user_id' => $isTiers ? $tiers->id : null,
        ]);
    }

    public function makeAbonnementForAdresse($adresse ,$abo = null)
    {
        if(!$abo){
            $abo = $this->makeAbo();
        }

        return factory(\App\Droit\Abo\Entities\Abo_users::class)->create([
            'abo_id'         => $abo->id,
            'adresse_id'     => $adresse->id,
        ]);
    }

    public function abonnementFacture($abonnement)
    {
        $abonnement->factures()->create([
            'abo_user_id' => $abonnement->id,
            'product_id'  => $abonnement->abo->current_product->id,
            'payed_at'    => null
        ]);
    }

    public function makeInscriptions($nbr = 1, $groupe = null)
    {
        // Create colloque
        $colloque = $this->colloque();
        $prices   = $colloque->prices->pluck('id')->all();

        for($x = 1; $x <= $nbr; $x++)
        {
            $personne  = $this->makeUser();

            factory(\App\Droit\Inscription\Entities\Inscription::class)->create([
                'user_id'     => $personne->id,
                'group_id'    => null,
                'price_id'    => $prices[0],
                'colloque_id' => $colloque->id
            ]);
        }

        if($groupe)
        {
            $detenteur = $this->makeUser();

            $group = factory(\App\Droit\Inscription\Entities\Groupe::class)->create([
                'user_id'     => $detenteur->id,
                'colloque_id' => $colloque->id
            ]);

            $inscriptions = factory(\App\Droit\Inscription\Entities\Inscription::class,2)->create([
                'user_id'     => null,
                'group_id'    => $group->id,
                'price_id'    => $prices[0],
                'colloque_id' => $colloque->id
            ]);

            $inscriptions = $inscriptions->map(function ($item, $key) {
                $item->inscription_no = '10-2016/1'.$key;

                $participant = new \App\Droit\Inscription\Entities\Participant();
                $participant->create(['name' => $this->faker->firstName, 'inscription_id' => $item->id ]);
                return $item;
            });
        }

        return $colloque->load(['inscriptions','prices']);
    }

    public function makeInscriptionForUser($user, $date, $colloque = null)
    {
        // Create colloque
        if(!$colloque){
            $colloque = $this->colloque();
        }

        $prices = $colloque->prices->pluck('id')->all();

        return factory(\App\Droit\Inscription\Entities\Inscription::class)->create([
            'user_id'     => $user->id,
            'group_id'    => null,
            'price_id'    => $prices[0],
            'colloque_id' => $colloque->id,
            'payed_at'    => null,
            'created_at'  => $date,
            'updated_at'  => $date
        ]);
    }
}