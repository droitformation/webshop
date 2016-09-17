<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \Symfony\Component\HttpFoundation\File\UploadedFile;

class AboTest extends TestCase {

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

        $this->visit(url('admin/abo'));
        $this->assertViewHas('abos');

        $this->click('addAbo');

        $this->seePageIs(url('admin/abo/create'));

        /*      
       $this->type('Bienne', 'ville');
        $this->press('Enregistrer');
        $this->seeInDatabase('users', [
            'id'         => $user->id,
            'first_name' => 'Terry'
        ]);
        */
    }

    public function testGetAboProductWithAttributes()
    {
        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        $make = new \tests\factories\ObjectFactory();
        $product = $make->product();
        $product = $make->addAttributesAbo($product);

        $this->visit(url('admin/abo/create'));
        $this->seePageIs(url('admin/abo/create'));

        $data = [
            'title'       => 'TestAbo',
            'price'       => 50,
            'plan'        => 'year',
            'products_id' => [$product->id]
        ];

        $response = $this->call('POST', '/admin/abo', $data);

        $this->seeInDatabase('abos', [
            'title' => 'TestAbo',
            'price' => '5000'
        ]);
    }
}
