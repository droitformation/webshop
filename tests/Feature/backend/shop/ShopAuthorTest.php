<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class ShopAuthorTest extends TestCase {

    protected $author;

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

    public function testShopAuthorCreate()
    {
        $data = [
            'first_name' => 'Cindy',
            'last_name'  => 'Leschaud',
        ];

        // filter to get all send orders
        $response = $this->call('POST', 'admin/shopauthor', $data);

        $this->assertDatabaseHas('shop_authors', $data);
    }

    public function testShopAuthorCreateFailsExist()
    {
        $author = factory(\App\Droit\Shop\Author\Entities\Author::class)->create([
            'first_name' => 'Jane2',
            'last_name'  => 'Doe2',
        ]);

        $data = ['first_name' => 'Jane2', 'last_name'  => 'Doe2',];

        // filter to get all send orders
        $response = $this->call('POST', 'admin/shopauthor', $data);

        $response->assertSessionHas('alert.style','danger');

    }

    public function testShopAuthorUpdate()
    {
        $author = factory(\App\Droit\Shop\Author\Entities\Author::class)->create([
            'first_name' => 'Jane',
            'last_name'  => 'Doe',
        ]);

        $data = [
            'id' => $author->id,
            'first_name' => 'Janet',
            'last_name'  => 'Lane',
        ];

        // filter to get all send orders
        $response = $this->put('admin/shopauthor/'.$author->id, $data);

        $this->assertDatabaseHas('shop_authors', [
            'id' => $author->id,
            'first_name' => 'Janet',
            'last_name'  => 'Lane',
        ]);
    }

    public function testShopAuthorDelete()
    {
        $author = factory(\App\Droit\Shop\Author\Entities\Author::class)->create();

        $response = $this->call('DELETE','admin/shopauthor/'.$author->id);

        $this->assertDatabaseMissing('shop_authors', ['id' => $author->id]);
    }
}
