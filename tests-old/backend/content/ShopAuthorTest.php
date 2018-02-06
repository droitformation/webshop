<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShopAuthorTest extends BrowserKitTest {

    protected $author;

    use DatabaseTransactions;

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

    public function testShopAuthorCreate()
    {
        $data = [
            'first_name' => 'Cindy',
            'last_name'  => 'Leschaud',
        ];

        // filter to get all send orders
        $response = $this->call('POST', 'admin/shopauthor', $data);

        $this->seeInDatabase('shop_authors', $data);
    }

    public function testShopAuthorCreateFailsExist()
    {
        $author = factory(App\Droit\Shop\Author\Entities\Author::class)->create([
            'first_name' => 'Jane2',
            'last_name'  => 'Doe2',
        ]);

        $data = ['first_name' => 'Jane2', 'last_name'  => 'Doe2',];

        // filter to get all send orders
        $response = $this->call('POST', 'admin/shopauthor', $data);

        $this->assertSessionHas('alert.style','danger');

    }

    public function testShopAuthorUpdate()
    {
        $author = factory(App\Droit\Shop\Author\Entities\Author::class)->create([
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

        $this->seeInDatabase('shop_authors', [
            'id' => $author->id,
            'first_name' => 'Janet',
            'last_name'  => 'Lane',
        ]);
    }

    public function testShopAuthorDelete()
    {
        $author = factory(App\Droit\Shop\Author\Entities\Author::class)->create();

        $this->visit('admin/shopauthor');

        $response = $this->call('DELETE','admin/shopauthor/'.$author->id);

        $this->notSeeInDatabase('shop_authors', ['id' => $author->id]);
    }
}
