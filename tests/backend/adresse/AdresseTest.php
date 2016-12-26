<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdresseTest extends TestCase {

    use DatabaseTransactions;

    protected $adresse;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();

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

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
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

        $this->visit(url('admin/user/'.$user->id));

        $this->assertEquals('New User', $user->name);
        $this->assertEquals('new.user@gmail.com', $user->email);
        $this->assertEquals(2, $user->orders->count());
        $this->assertEquals($order1->id, $user->orders->contains('id',$order1->id));
        $this->assertEquals($order2->id, $user->orders->contains('id',$order2->id));
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

    public function testCreateAdresseForUser()
    {
        $user  = factory(App\Droit\User\Entities\User::class)->create();

        $response = $this->call('GET', 'admin/adresse/create/'.$user->id);

        $this->assertViewHas('user');
    }
}
