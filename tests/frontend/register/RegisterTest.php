<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;

class RegisterTest extends BrowserKitTest {
    
    protected $worker;

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
    
    /**
     * Inscription from frontend
     * @return void
     */
    public function testCanRegister()
    {
        $make = new \tests\factories\ObjectFactory();

        $colloque = $make->colloque();
        $user     = $make->makeUser();

        $this->actingAs($user);
        $date = \Carbon\Carbon::now()->subDays(32)->toDateString();

        $inscription1 = $make->makeInscriptionForUser($user, $date);
        $inscription2 = $make->makeInscriptionForUser($user, $date);

        $this->visit('/pubdroit/colloque/'.$colloque->id);
        $this->assertViewHas('colloque');

        $this->see($colloque->title);
    }

    public function testCantRegisterHasRappel()
    {
        $make = new \tests\factories\ObjectFactory();

        $colloque = $make->colloque();
        $user     = $make->makeUser();

        $this->actingAs($user);
        $date = \Carbon\Carbon::now()->subDays(32)->toDateString();

        $inscription1 = $make->makeInscriptionForUser($user, $date);
        $inscription2 = $make->makeInscriptionForUser($user, $date);

        $inscription1->rappels()->create([
            'user_id'     => $user->id,
            'colloque_id' => $inscription1->colloque_id,
        ]);

        $inscription2->rappels()->create([
            'user_id'      => $user->id,
            'colloque_id'  => $inscription2->colloque_id,
        ]);

        $this->visit('/pubdroit/colloque/'.$colloque->id);
        $this->assertViewHas('colloque');

        $this->see('Vous avez des payements en attente');

    }
}
