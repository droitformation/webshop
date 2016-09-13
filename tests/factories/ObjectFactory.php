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

    public function makeUser()
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
            'profession_id' => array_rand($this->professions, 1),
            'telephone'     => '032 690 00 23',
            'mobile'        => '032 690 00 23',
            'fax'           => null,
            'adresse'       => $this->faker->address,
            'npa'           => $this->faker->postcode,
            'ville'         => $this->faker->city,
            'canton_id'     => array_rand($this->cantons, 1),
            'pays_id'       => 208,
            'type'         => 1,
            'user_id'      => $user->id,
            'livraison'    => 1
        ]);

        $user->adresses()->save($adresse);

        return $user;
    }

    public function user($nbr = false)
    {
        if($nbr)
        {
            for ($x = 0; $x <= $nbr; $x++)
            {
                $this->makeUser();
            }
        }

        return $this->makeUser();;
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

        return $colloque;
        
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

    public function product($nbr)
    {
        $images = \File::files('files/products');
        $images = collect($images)->map(function ($name) {
            $file = explode('/', $name);
            $file = end($file);

            return $file;
        })->toArray();

        for ($x = 0; $x <= $nbr; $x++) {
            factory(\App\Droit\Shop\Product\Entities\Product::class)->create([
                'title'           => $this->faker->sentence,
                'teaser'          => $this->faker->sentence,
                'image'           => $images[array_rand($images)],
                'description'     => $this->faker->text() ,
                'weight'          => 500,
                'sku'             => 10,
                'price'           => $this->faker->numberBetween(10000,30000),
            ]);
        }

    }

    public function order($nbr)
    {
        $product = new \App\Droit\Shop\Product\Entities\Product();
        $exist   = $product->all();

        if($exist->isEmpty())
        {
            $this->product($nbr);
        }

        for ($x = 1; $x <= $nbr; $x++)
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
        }
        
    }
}