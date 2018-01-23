<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AccountEventTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $user = factory(\App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        \DB::beginTransaction();
    }

    public function tearDown()
    {
        \Mockery::close();
        \DB::rollBack();
        parent::tearDown();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUpdateAdresse()
    {
        $make = new \tests\factories\ObjectFactory();
        $person = $make->makeUser(['email' => 'info@domain.ch','first_name' => 'Cindy', 'last_name' => 'Leschaud']);

        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1]);
        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => $person->email]);
        $user->subscriptions()->attach([$newsletter->id]);

        $old_email = $person->email;
        $new_email = 'new.user@gmail.com';

        $this->assertEquals('info@domain.ch',$old_email);

        $this->assertDatabaseHas('users', ['email' => $old_email]);
        $this->assertDatabaseHas('adresses', ['email' => $old_email]);

        $this->assertDatabaseHas('newsletter_users', ['email' => $old_email]);
        $this->assertDatabaseMissing('newsletter_users', ['email' => $new_email]);

        $response = $this->put('admin/user/'.$person->id, [
            'id'    => $person->id,
            'first_name' => $person->first_name,
            'last_name'  => $person->last_name,
            'email' => $new_email
        ]);

        $this->assertDatabaseHas('newsletter_users', ['email' => $new_email]);
        $this->assertDatabaseMissing('newsletter_users', ['email' => $old_email, 'deleted_at' => null]);

        $person = $person->fresh();

        $this->assertEquals($person->email,$new_email);
        $this->assertDatabaseHas('users', ['email' => $new_email]);
        $this->assertDatabaseHas('adresses', ['email' => $new_email]);
        $this->assertDatabaseMissing('users', ['email' => $old_email]);
        $this->assertDatabaseMissing('adresses', ['email' => $old_email]);

    }
}
