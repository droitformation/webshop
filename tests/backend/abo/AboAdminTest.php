<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \Symfony\Component\HttpFoundation\File\UploadedFile;

class AboAdminTest extends TestCase {

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }
    
    public function testAddAbo()
    {
        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        $this->visit('admin/abo');
        $this->assertViewHas('abos');

        $this->click('addAbo');

        $this->seePageIs(url('admin/abo/create'));
    }

    public function testGetAboProductWithAttributes()
    {
        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        // Make new product and add the attributes
        $make = new \tests\factories\ObjectFactory();

        $product = $make->product();
        $product = $make->addAttributesAbo($product);

        $this->visit('admin/abo/create');
        $this->seePageIs('admin/abo/create');

        $data = [
            'title'       => 'TestAbo',
            'price'       => 50,
            'plan'        => 'year',
            'shipping'    => '12',
            'products_id' => [$product->id]
        ];

        $response = $this->call('POST', '/admin/abo', $data);

        $this->seeInDatabase('abos', [
            'title'    => 'TestAbo',
            'price'    => '5000',
            'shipping' => '1200'
        ]);
    }

}
