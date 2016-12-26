<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ColloqueItemTest extends TestCase {

    use DatabaseTransactions;

    protected $colloque;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();
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

        $data = [
            'colloque_id' => $colloque->id,
            'price'       => 10,
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'cindy'
        ];

        $response = $this->call('POST', '/admin/price', ['data' => http_build_query($data)]);

        $this->seeInDatabase('colloque_prices', [
            'colloque_id' => $colloque->id,
            'price'       => 1000,
            'remarque'    => 'testing',
            'type'        => 'public',
            'description' => 'cindy'
        ]);
    }
}
