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
        $first_name = $this->faker->firstName;
        $last_name  = $this->faker->lastName;
        $email      = $this->faker->email;

        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'email'      => $email,
            'password'   => bcrypt('secret')
        ]);

        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create([
            'civilite_id'   => $this->faker->numberBetween(1,4),
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
            $adresse->specialisations()->attach($data['specialisations']);
        }

        if(isset($data['members']))
        {
            $adresse->members()->attach($data['members']);
        }

        return $user;
    }

    public function user($data = [])
    {
        if(count($data) > 1)
        {
            for ($x = 0; $x < count($data); $x++)
            {
                $this->makeUser($data[$x]);
            }
        }

        return $this->makeUser();
    }

    public function adresse()
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
            'user_id'      => 0,
            'livraison'    => 1
        ]);
    }

    public function makeAdmin($user)
    {
        $user->roles()->attach(1);

        return $user;
    }

    public function colloque()
    {
        $compte = factory(\App\Droit\Compte\Entities\Compte::class)->create();

        $colloque = factory(\App\Droit\Colloque\Entities\Colloque::class)->create([
            'titre'           => $this->faker->sentence,
            'soustitre'       => $this->faker->sentence,
            'sujet'           => $this->faker->sentence,
            'remarques'       => $this->faker->text(),
            'start_at'        => \Carbon\Carbon::now()->addMonths(2),
            'end_at'          => null,
            'registration_at' => \Carbon\Carbon::now()->addMonth(),
            'active_at'       => null,
            'organisateur'    => $this->faker->company,
            'location_id'     => 1,
            'compte_id'       => $compte->id,
            'visible'         => 1,
            'bon'             => 1,
            'facture'         => 0,
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

    public function order($nbr)
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

            $order = factory(\App\Droit\Shop\Order\Entities\Order::class)->create([
                'user_id'     => 1,
                'coupon_id'   => null,
                'payement_id' => 1,
                'order_no'    => '2016-0000000'.$x.'',
                'amount'      => $amount,
                'shipping_id' => 1,
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

    public function abonnementFacture($abonnement)
    {
        $abonnement->factures()->create([
            'abo_user_id' => $abonnement->id,
            'product_id'  => $abonnement->abo->current_product->id,
            'payed_at'    => null
        ]);
    }
}