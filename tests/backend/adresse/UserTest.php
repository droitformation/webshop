<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase {

    use DatabaseTransactions;

    protected $adresse;
    protected $user;

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
    
    public function testChangePassword()
    {
        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();

        $user->roles()->attach(1);

        $this->actingAs($user);

        $this->assertTrue(Auth::check());

        $this->visit(url('admin/user/'.$user->id));
        $this->seePageIs(url('admin/user/'.$user->id));
        $this->assertViewHas('user');

        $this->type('Terry', 'first_name');

        $this->press('Enregistrer');

        $this->seeInDatabase('users', [
            'id'         => $user->id,
            'first_name' => 'Terry'
        ]);
    }
}
