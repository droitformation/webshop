<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\BrowserKitTesting\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class PriceTest extends BrowserKitTest {

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

    public function testCreateNewPrice()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $this->visit('/admin/colloque/'.$colloque->id)->see($colloque->titre);

        $prices =[
            'colloque_id' => $colloque->id,
            'price'       => '10.00', // with quotes else json is not formatted correctly
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'cindy',
        ];

        $data = ['price' => $prices];

        $this->json('POST', '/vue/price', $data)->seeJson($prices);

        $this->seeInDatabase('colloque_prices', [
            'colloque_id' => $colloque->id,
            'price'       => 1000,
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'cindy'
        ]);
    }

    public function testUpdatePrice()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $this->visit('/admin/colloque/'.$colloque->id)->see($colloque->titre);

        $price = factory(App\Droit\Price\Entities\Price::class)->create([
            'colloque_id' => $colloque->id,
            'price'       => '12.00', // with quotes else json is not formatted correctly
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'cindy',
            'rang'        => 1,
        ]);

        // Fake update
        $prices = [
            'id'          => $price->id,
            'colloque_id' => $colloque->id,
            'price'       => '15.00', // with quotes else json is not formatted correctly
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'cindy',
        ];

        $data = ['price' => $prices];

        $this->json('PUT', '/vue/price/'.$price->id, $data)->seeJson($prices);

        $this->seeInDatabase('colloque_prices', [
            'id'          => $price->id,
            'colloque_id' => $colloque->id,
            'price'       => 1500,
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'cindy'
        ]);
    }

    public function testDeletePrice()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $this->visit('/admin/colloque/'.$colloque->id)->see($colloque->titre);

        $price = factory(App\Droit\Price\Entities\Price::class)->create([
            'colloque_id' => $colloque->id,
            'price'       => '12.00', // with quotes else json is not formatted correctly
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'cindy',
            'rang'        => 1,
        ]);

        $this->json('DELETE', '/vue/price/'.$price->id)->seeJson([]);
    }
}
