<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExportadresseTest extends BrowserKitTest {

    protected $mock;
    protected $colloque;
    protected $groupe;
    protected $interface;
    protected $worker;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }
    
	public function testPrepareAdresse()
	{
        $exporter = new \App\Droit\Generate\Export\ExportAdresse();

		$adresse = factory(App\Droit\Adresse\Entities\Adresse::class)->make([
            'civilite_id'   => 2,
            'first_name'    => 'Cindy',
            'last_name'     => 'Leschaud',
            'email'         => 'cindy.leschaud@gmail.com',
            'company'       => 'DesignPond',
            'profession_id' => 1,
            'telephone'     => '032 690 00 23',
            'mobile'        => '032 690 00 23',
            'fax'           => null,
            'adresse'       => 'Ruelle de l\'hôtel de ville 3',
            'npa'           => '2520',
            'ville'         => 'La Neuveville',
            'canton_id'     => 6,
            'pays_id'       => 208,
        ]);

        $adresses = collect([$adresse]);

        $converted = $exporter->prepareAdresse($adresses);
        $converted = $converted->toArray();

        $expect = [
            'Madame', 'Cindy','Leschaud', 'cindy.leschaud@gmail.com', 'Avocat', 'DesignPond', '032 690 00 23', '032 690 00 23',
            'Ruelle de l\'hôtel de ville 3', '', '', '2520', 'La Neuveville', 'Berne (BE)', 'Suisse',
        ];

        $this->assertEquals($expect, $converted[0]);

    }
}
