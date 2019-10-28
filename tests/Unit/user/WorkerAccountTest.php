<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\ResetTbl;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkerAccountTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testCreateAccountWithSubstitudeEmailAndExistintgEmail()
    {
        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser();

        $email = $user->email;

        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create([
            'type'    => 1,
            'email'   => $user->email,
            'user_id' => null
        ]);

        $data = ['password' => 123456];

        $worker = \App::make('App\Droit\User\Worker\AccountWorkerInterface');
        $user = $worker->setAdresse($adresse)->createAccount($data);

        $adresse = $adresse->fresh();

        $this->assertTrue(isset($adresse->user));
        $this->assertEquals($email,$adresse->user->username);

        $attempt = \Auth::attempt(['email' => $adresse->user->email, 'password' => '123456']);

        $this->assertTrue($attempt);
    }

    /**
     * @expectedException \Illuminate\Validation\ValidationException
     */
    public function testValidationFails()
    {
        $worker = \App::make('App\Droit\User\Worker\AccountWorkerInterface');
        $reponse = $worker->createAccount([]);
    }

    public function testSetAdresse()
    {
        $make    = new \tests\factories\ObjectFactory();

        $adresse = $make->adresse();

        $worker = \App::make('App\Droit\User\Worker\AccountWorkerInterface');
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

        $worker = \App::make('App\Droit\User\Worker\AccountWorkerInterface');
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

        $worker = \App::make('App\Droit\User\Worker\AccountWorkerInterface');
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

        $worker = \App::make('App\Droit\User\Worker\AccountWorkerInterface');
        $user = $worker->createAccount($data);

        $this->assertInstanceOf(\App\Droit\User\Entities\User::class, $user);
        $this->assertTrue(isset($user->adresse_contact));
        $this->assertSame('Jane Doe',$user->adresse_contact->name);

        $attempt = \Auth::attempt(['email' => 'info@designpond.ch', 'password' => '123456']);
        $this->assertTrue($attempt);
    }

    public function testExtractAdresse()
    {
        // create user with 2 adresses
        $make = new \tests\factories\ObjectFactory();
        $user    = $make->makeUser(); // one adresse already

        $original = $user;

        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create([
            'email'   => null,
            'user_id' => $user->id,
        ]);

        $this->assertSame($adresse->user_id,$user->id);

        // extract 1 adresse
        $response = $this->call('POST', 'admin/adresse/convert', ['id' => $adresse->id]);
        $location = $response->headers->get('Location');

        $path = explode('/',$location);
        $path = end($path);

        $response = $this->get('admin/user/'.$path);
        $response->assertStatus(200);

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $newuser = $content['user'];

        $adresse = $adresse->fresh();

        $this->assertNotEquals($adresse->user_id,$user->id);

        $user =  $user->fresh();

        $this->assertEquals($user->email, $original->email);

        $this->assertNotEmpty($adresse->email);
        $this->assertNotNull($adresse->user_id);
        $this->assertTrue(substr(strrchr($adresse->email, "@"), 1) == 'publications-droit.ch');
        $this->assertTrue(substr(strrchr($newuser->email, "@"), 1) == 'publications-droit.ch');
    }

    public function testsRestoreAccount()
    {
        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser();

        $adresse = $user->adresses->first();
        $adresse->delete();
        $user->delete();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'deleted_at' => null
        ]);

        $this->assertDatabaseMissing('adresses', [
            'id' => $adresse->id,
            'user_id' => $user->id,
            'deleted_at' => null
        ]);

        $worker = \App::make('App\Droit\User\Worker\AccountWorkerInterface');
        $worker->restore($user->id);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null
        ]);

        $this->assertDatabaseHas('adresses', [
            'id' => $adresse->id,
            'user_id' => $user->id,
            'deleted_at' => null
        ]);

    }

    public function testAllowAlreadyDeletedUserEmail()
    {
        $make = new \tests\factories\ObjectFactory();
        $user = $make->makeUser();

        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create([
            'email'   => $user->email,
            'user_id' => null,
        ]);

        $user->delete();

        $response = $this->call('POST', 'admin/adresse/convert', ['id' => $adresse->id]);
        $location = $response->headers->get('Location');

        $path = explode('/',$location);
        $path = end($path);

        $response = $this->get('admin/user/'.$path);
        $response->assertStatus(200);

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $newuser = $content['user'];

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'deleted_at' => null
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $newuser->id,
            'email' => $user->email,
            'deleted_at' => null
        ]);

    }
}
