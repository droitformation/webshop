<?php

namespace Tests\Feature\backend\user;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class SearchAdminAdresseTest extends TestCase
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

    public function testAdressesList()
    {
        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create([
            'first_name' => 'SG878sdv',
            'last_name'  => 'User',
            'email'      => 'SG878sdv@gmail.com',
        ]);

        $response = $this->call('POST', 'admin/adresses', ['term' => 'SG878sdv']);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $adresses = $content['adresses'];

        $this->assertTrue($adresses->contains('first_name','SG878sdv'));
        $this->assertTrue($adresses->contains('email','SG878sdv@gmail.com'));
    }


    public function testConvertAdresseToUser()
    {
        $make = new \tests\factories\ObjectFactory();

        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create([
            'first_name' => 'New',
            'last_name'  => 'User',
            'email'      => 'new.user@gmail.com',
            'user_id'    => null,
        ]);

        $order1 = $make->makeAdresseOrder($adresse->id);
        $order2 = $make->makeAdresseOrder($adresse->id);

        $adresse->orders()->saveMany([$order1,$order2]);

        $response = $this->call('POST', 'admin/adresse/convert', ['id' => $adresse->id, 'password' => 'cindy2']);
        $location = $response->headers->get('Location');

        $path = explode('/',$location);
        $path = end($path);

        $response = $this->get('admin/user/'.$path);
        $response->assertStatus(200);

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $user    = $content['user'];

        $this->assertEquals('New User', $user->name);
        $this->assertEquals('new.user@gmail.com', $user->email);
        $this->assertEquals(2, $user->orders->count());
        $this->assertEquals($order1->id, $user->orders->contains('id',$order1->id));
        $this->assertEquals($order2->id, $user->orders->contains('id',$order2->id));
    }

    public function testConvertAdresseToUserWithOnlyCompanyName()
    {
        $make = new \tests\factories\ObjectFactory();

        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create([
            'first_name' => '',
            'last_name'  => '',
            'company'    => 'Our Company',
            'email'      => 'new.user@gmail.com',
        ]);

        $response = $this->call('POST', 'admin/adresse/convert', ['id' => $adresse->id, 'password' => 'cindy2']);

        $location = $response->headers->get('Location');

        $path = explode('/',$location);
        $path = end($path);

        $response = $this->get('admin/user/'.$path);
        $response->assertStatus(200);

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $user    = $content['user'];

        $this->assertEquals('Our Company', $user->name);
        $this->assertEquals('new.user@gmail.com', $user->email);

        $this->assertDatabaseHas('users', [
            'id'         => $user->id,
            'first_name' => '',
            'last_name'  => '',
            'company'    => 'Our Company'
        ]);
    }

    // New implementation put substitute email and password 123456
/*    public function testConvertAdresseToUserFails()
    {
        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create([
            'first_name' => 'New',
            'last_name'  => 'User',
            'email'      => null,
        ]);

        $response = $this->call('POST', 'admin/adresse/convert', ['id' => $adresse->id]);

        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors('password');
    }*/

    public function testDeleteAdresse()
    {
        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create([
            'first_name' => 'New',
            'last_name'  => 'User',
            'email'      => null,
        ]);

        $response = $this->call('DELETE','admin/adresse/'.$adresse->id);

        $this->assertDatabaseMissing('adresses', [
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

        $user    = factory(\App\Droit\User\Entities\User::class)->create();
        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create([
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

        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create();

        $make->makeAdresseOrder($adresse->id);

        $validator = new \App\Droit\Adresse\Worker\AdresseValidation($adresse);
        $validator->activate();

        $this->expectExceptionMessage('L\'adresse est lié à des commandes');
    }

    public function testUserName()
    {
        $user1 = factory(\App\Droit\User\Entities\User::class)->create([
            'first_name' => 'Jane',
            'last_name'  => 'Doe',
        ]);

        $user2 = factory(\App\Droit\User\Entities\User::class)->create([
            'first_name' => '',
            'last_name'  => '',
            'company'  => 'Acme',
        ]);

        $user3 = factory(\App\Droit\User\Entities\User::class)->create([
            'first_name' => 'George',
            'last_name'  => '',
            'company'    => '',
        ]);

        $user4 = factory(\App\Droit\User\Entities\User::class)->create([
            'first_name' => '',
            'last_name'  => 'Martin',
            'company'    => '',
        ]);

        $user5 = factory(\App\Droit\User\Entities\User::class)->create([
            'first_name' => '',
            'last_name'  => 'Martin',
            'company'    => 'Acme',
        ]);

        $this->assertSame('Jane Doe',$user1->name);
        $this->assertSame('Acme',$user2->name);
        $this->assertSame('George',$user3->name);
        $this->assertSame('Martin',$user4->name);
        $this->assertSame('Acme',$user5->name);
    }
}
