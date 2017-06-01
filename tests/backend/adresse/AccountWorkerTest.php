<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AccountWorkerTest extends BrowserKitTest {

    use DatabaseTransactions;

    protected $adresse;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $user = factory(App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        DB::beginTransaction();
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }

    public function testSetAdresse()
    {
        $make    = new \tests\factories\ObjectFactory();

        $adresse = $make->adresse();

        $worker = App::make('App\Droit\User\Worker\AccountWorkerInterface');
        $worker->setAdresse($adresse);

        $this->assertEquals($adresse->id,$worker->adresse->id);

    }

    public function testPrepareDataWithAdresse()
    {
        $make    = new \tests\factories\ObjectFactory();
        $adresse = $make->adresse();

        $data = ['password' => 1234];

        $expect = [
            'first_name' => $adresse->first_name,
            'last_name'  => $adresse->last_name,
            'company'    => $adresse->company,
            'email'      => $adresse->email,
            'password'   => 1234,
            'adresse'    => $adresse->adresse,
            'npa'        => $adresse->npa,
            'ville'      => $adresse->ville,
        ];

        $worker = App::make('App\Droit\User\Worker\AccountWorkerInterface');
        $worker->setAdresse($adresse)->prepareData($data);

        $this->assertEquals($expect, $worker->data);

    }

    public function testPrepareData()
    {
        $data = [
            'first_name' => 'Jane',
            'last_name'  => 'Doe',
            'company'    => 'DesignPond',
            'email'      => 'info@designpond.ch',
            'password'   => 123456,
            'adresse'    => 'Rue du test 45',
            'npa'        => '2345',
            'ville'      => 'Bienne',
        ];

        $worker = App::make('App\Droit\User\Worker\AccountWorkerInterface');
        $worker->prepareData($data);

        $this->assertEquals($data, $worker->data); // password will be encrypted after validation

        $worker->validate()->makeUser();

        $this->assertInstanceOf(\App\Droit\User\Entities\User::class, $worker->user);
    }

    public function testCreateAccount()
    {
        $data = [
            'civilite_id' => 1,
            'first_name'  => 'Jane',
            'last_name'   => 'Doe',
            'company'     => 'DesignPond',
            'email'       => 'info@designpond.ch',
            'password'    => 123456,
            'adresse'     => 'Rue du test 45',
            'npa'         => '2345',
            'ville'       => 'Bienne',
        ];

        $worker = App::make('App\Droit\User\Worker\AccountWorkerInterface');
        $user = $worker->createAccount($data);

        $this->assertInstanceOf(\App\Droit\User\Entities\User::class, $user);
        $this->assertTrue(isset($user->adresse_contact));
        $this->assertSame('Jane Doe',$user->adresse_contact->name);

        $attempt = Auth::attempt(['email' => 'info@designpond.ch', 'password' => '123456']);
        $this->assertTrue($attempt);
    }

    public function testValidationFails()
    {
        $worker = App::make('App\Droit\User\Worker\AccountWorkerInterface');
        
        try{
            $reponse = $worker->createAccount([]);
        }
        catch (Exception $e) {
            $this->assertInstanceOf('Illuminate\Validation\ValidationException', $e);
        }
    }
}
