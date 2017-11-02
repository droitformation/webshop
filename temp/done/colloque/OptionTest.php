<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class OptionTest extends BrowserKitTest {

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

    public function testCreateNewOption()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $this->visit('/admin/colloque/'.$colloque->id)->see($colloque->titre);

        $options =[
            'colloque_id' => $colloque->id,
            'title'       => 'testing',
            'type'        => 'checkbox'
        ];

        $data = ['option' => $options];

        $this->json('POST', '/vue/option', $data)->seeJson($options);

        $this->seeInDatabase('colloque_options', [
            'colloque_id' => $colloque->id,
            'title'       => 'testing',
            'type'        => 'checkbox'
        ]);
    }

    public function testCreateNewOptionWithGroupes()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $this->visit('/admin/colloque/'.$colloque->id)->see($colloque->titre);

        $options = [
            'colloque_id' => $colloque->id,
            'title'       => 'testing',
            'type'        => 'checkbox',
            'groupe'      => [
                [
                    'text'       => 'Premier groupe',
                    'colloque_id'=> $colloque->id,
                ]
            ]
        ];

        $data = ['option' => $options];

        $this->json('POST', '/vue/option', $data);

        $this->seeInDatabase('colloque_options', [
            'colloque_id' => $colloque->id,
            'title'       => 'testing',
            'type'        => 'checkbox'
        ]);

        $this->seeInDatabase('colloque_option_groupes', [
            'colloque_id' => $colloque->id,
            'text'       => 'Premier groupe',
        ]);
    }

    public function testUpdateOption()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $this->visit('/admin/colloque/'.$colloque->id)->see($colloque->titre);

        $option = factory(App\Droit\Option\Entities\Option::class)->create([
            'colloque_id' => $colloque->id,
            'title'       => 'testing',
            'type'        => 'checkbox'
        ]);

        // Fake update
        $options = [
            'id'          => $option->id,
            'colloque_id' => $colloque->id,
            'title'       => 'other',
            'type'        => 'checkbox'
        ];

        $data = ['option' => $options];

        $this->json('PUT', '/vue/option/'.$option->id, $data)->seeJson($options);

        $this->seeInDatabase('colloque_options', [
            'id'          => $option->id,
            'colloque_id' => $colloque->id,
            'title'       => 'other',
            'type'        => 'checkbox'
        ]);
    }

    public function testAddNewGroupItem()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $groupe = [
            'text'       => 'Premier groupe',
            'colloque_id'=> $colloque->id,
            'option_id'  => $colloque->options->first()->id
        ];

        $this->json('POST', '/vue/groupe', $groupe);

        $this->seeInDatabase('colloque_option_groupes', [
            'colloque_id' => $colloque->id,
            'text'       => 'Premier groupe',
        ]);
    }

    public function testDeleteOption()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $this->visit('/admin/colloque/'.$colloque->id)->see($colloque->titre);

        $option = factory(App\Droit\Option\Entities\Option::class)->create([
            'colloque_id' => $colloque->id,
            'title'       => 'testing',
            'type'        => 'checkbox'
        ]);

        $this->json('DELETE', '/vue/option/'.$option->id)->seeJson([]);
    }
}
