<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdresseTest extends BrowserKitTest {

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

    public function testAdressesList()
    {
        $this->visit('admin/adresses');
        $this->assertViewHas('adresses');

        $adresse = factory(App\Droit\Adresse\Entities\Adresse::class)->create([
            'first_name' => 'SG878sdv',
            'last_name'  => 'User',
            'email'      => 'SG878sdv@gmail.com',
        ]);

        $response = $this->call('POST', 'admin/adresses', ['term' => 'SG878sdv']);

        $content   = $this->followRedirects()->response->getOriginalContent();
        $content   = $content->getData();
        $adresses  = $content['adresses'];

        $this->assertTrue($adresses->contains('first_name','SG878sdv'));
        $this->assertTrue($adresses->contains('email','SG878sdv@gmail.com'));
    }
    
	public function testConvertAdresseToUser()
	{
        $make = new \tests\factories\ObjectFactory();
        
        $adresse = factory(App\Droit\Adresse\Entities\Adresse::class)->create([
            'first_name' => 'New',
            'last_name'  => 'User',
            'email'      => 'new.user@gmail.com',
        ]);

        $order1 = $make->makeAdresseOrder($adresse->id);
        $order2 = $make->makeAdresseOrder($adresse->id);
        
        $adresse->orders()->saveMany([$order1,$order2]);

        $response = $this->call('POST', 'admin/adresse/convert', ['id' => $adresse->id, 'password' => 'cindy2']);

        $content = $this->followRedirects()->response->getOriginalContent();
        $content = $content->getData();
        $user    = $content['user'];

        $this->visit('admin/user/'.$user->id);

        $this->assertEquals('New User', $user->name);
        $this->assertEquals('new.user@gmail.com', $user->email);
        $this->assertEquals(2, $user->orders->count());
        $this->assertEquals($order1->id, $user->orders->contains('id',$order1->id));
        $this->assertEquals($order2->id, $user->orders->contains('id',$order2->id));
	}

    public function testConvertAdresseToUserWithOnlyCompanyName()
    {
        $make = new \tests\factories\ObjectFactory();

        $adresse = factory(App\Droit\Adresse\Entities\Adresse::class)->create([
            'first_name' => '',
            'last_name'  => '',
            'company'    => 'Our Company',
            'email'      => 'new.user@gmail.com',
        ]);

        $response = $this->call('POST', 'admin/adresse/convert', ['id' => $adresse->id, 'password' => 'cindy2']);

        $content = $this->followRedirects()->response->getOriginalContent();
        $content = $content->getData();
        $user    = $content['user'];

        $this->visit('admin/user/'.$user->id);

        $this->assertEquals('Our Company', $user->name);
        $this->assertEquals('new.user@gmail.com', $user->email);

        $this->seeInDatabase('users', [
            'id'         => $user->id,
            'first_name' => '',
            'last_name'  => '',
            'company'    => 'Our Company'
        ]);
    }

    public function testConvertAdresseToUserFails()
    {
        $adresse = factory(App\Droit\Adresse\Entities\Adresse::class)->create([
            'first_name' => 'New',
            'last_name'  => 'User',
            'email'      => null,
        ]);

        $response = $this->call('POST', 'admin/adresse/convert', ['id' => $adresse->id]);

        $this->assertSessionHasErrors('email');
        $this->assertSessionHasErrors('password');
    }

    public function testCreateAdresseForUserForm()
    {
        $user  = factory(App\Droit\User\Entities\User::class)->create();

        $this->visit('admin/adresse/create/'.$user->id);
        $this->assertViewHas('user');

        // Create adresse with user data but change last_name
        $this->type($user->first_name, 'first_name');
        $this->type('Jones', 'last_name');
        $this->type($user->email, 'email');
        $this->type('Rue du test 23', 'adresse');
        $this->type('1234', 'npa');
        $this->type('Bienne', 'ville');
        $this->press('Enregistrer');

        $this->seeInDatabase('adresses', [
            'user_id'    => $user->id,
            'first_name' => $user->first_name,
            'last_name'  => 'Jones',
            'email'      => $user->email,
            'adresse'    => 'Rue du test 23',
            'npa'        => '1234',
            'ville'      => 'Bienne',
        ]);
    }

    public function testNewAdresse()
    {
        $this->visit('admin/adresse/create');

        $this->type('Terry', 'first_name');
        $this->type('Jones', 'last_name');
        $this->type('terry.jones@gmail.com', 'email');
        $this->type('Rue du test 23', 'adresse');
        $this->type('1234', 'npa');
        $this->type('Bienne', 'ville');
        $this->press('Enregistrer');

        $this->seeInDatabase('adresses', [
            'first_name' => 'Terry',
            'last_name'  => 'Jones',
            'email'      => 'terry.jones@gmail.com',
            'adresse'    => 'Rue du test 23',
            'npa'        => '1234',
            'ville'      => 'Bienne',
        ]);
    }

    public function testDeleteAdresse()
    {
        $adresse = factory(App\Droit\Adresse\Entities\Adresse::class)->create([
            'first_name' => 'New',
            'last_name'  => 'User',
            'email'      => null,
        ]);

        $this->visit('admin/adresse/'.$adresse->id);

        $response = $this->call('DELETE','admin/adresse/'.$adresse->id);

        $this->notSeeInDatabase('adresses', [
            'id' => $adresse->id,
            'deleted_at' => null
        ]);
    }

    /**
     * @expectedException \App\Exceptions\AdresseRemoveException
     */
    public function testDeleteAdresseValidation()
    {
        $make = new \tests\factories\ObjectFactory();

        $user    = factory(App\Droit\User\Entities\User::class)->create();
        $adresse = factory(App\Droit\Adresse\Entities\Adresse::class)->create([
            'user_id' => $user->id,
        ]);

        // Order for user linked to adresse
        $make->order(2, $user->id);

        $validator = new \App\Droit\Adresse\Worker\AdresseValidation($adresse);
        $validator->activate();

        $this->expectExceptionMessage('L\'adresse est lié à des commandes, L\'adresse est rattaché à un compte utilisateur');
    }

    /**
     * @expectedException \App\Exceptions\AdresseRemoveException
     */
    public function testDeleteAdresseWithOrdersValidation()
    {
        $make = new \tests\factories\ObjectFactory();

        $adresse = factory(App\Droit\Adresse\Entities\Adresse::class)->create();

        $make->makeAdresseOrder($adresse->id);

        $validator = new \App\Droit\Adresse\Worker\AdresseValidation($adresse);
        $validator->activate();

        $this->expectExceptionMessage('L\'adresse est lié à des commandes');
    }
}
