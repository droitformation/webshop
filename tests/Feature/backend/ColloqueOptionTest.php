<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ColloqueOptionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testCreateNewOption()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $options =[
            'colloque_id' => $colloque->id,
            'title'       => 'testing',
            'type'        => 'checkbox'
        ];

        $data = ['option' => $options];

        $this->json('POST', '/vue/option', $data)->assertJsonFragment($options);

        $this->assertDatabaseHas('colloque_options', [
            'colloque_id' => $colloque->id,
            'title'       => 'testing',
            'type'        => 'checkbox'
        ]);
    }

    public function testCreateNewOptionWithGroupes()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

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

        $this->assertDatabaseHas('colloque_options', [
            'colloque_id' => $colloque->id,
            'title'       => 'testing',
            'type'        => 'checkbox'
        ]);

        $this->assertDatabaseHas('colloque_option_groupes', [
            'colloque_id' => $colloque->id,
            'text'       => 'Premier groupe',
        ]);
    }

    public function testUpdateOption()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $option = factory(\App\Droit\Option\Entities\Option::class)->create([
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

        $this->json('PUT', '/vue/option/'.$option->id, $data)->assertJsonFragment($options);

        $this->assertDatabaseHas('colloque_options', [
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

        $this->assertDatabaseHas('colloque_option_groupes', [
            'colloque_id' => $colloque->id,
            'text'       => 'Premier groupe',
        ]);
    }

    public function testDeleteOption()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $option = factory(\App\Droit\Option\Entities\Option::class)->create([
            'colloque_id' => $colloque->id,
            'title'       => 'testing',
            'type'        => 'checkbox'
        ]);

        $reponse = $this->json('DELETE', '/vue/option/'.$option->id)->assertJsonFragment([]);

        $this->assertDatabaseMissing('colloque_options', [
            'id' => $option->id,
            'deleted_at'  => null
        ]);
    }
}
