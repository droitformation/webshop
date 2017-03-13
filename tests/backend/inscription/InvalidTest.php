<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvalidTest extends BrowserKitTest {

    use DatabaseTransactions;

    protected $colloque;
    protected $inscription_normal;
    protected $inscription_group;
    protected $inscriptions;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);

        // Create colloque
        $make = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1, 1);

        $inscriptions = $colloque->inscriptions;

        $this->inscriptions = $inscriptions;
        $this->colloque     = $colloque;

        $this->inscription_normal = $inscriptions->filter(function ($inscription, $key) {
            return $inscription->user_id;
        })->first();

        $this->inscription_group = $inscriptions->filter(function ($inscription, $key) {
            return $inscription->group_id;
        })->first();

    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }

    /**
     *
     * @return void
     */
    public function testDeletedUser()
    {
        $normal = $this->inscription_normal;

        $user = $normal->user;
        // Test user deleted
        $normal->user()->delete();

        $display = new \App\Droit\Inscription\Entities\Invalid($normal);
        $display->trashedUser()->getAdresse();

        $this->assertEquals(['Compte ID '.$user->id.' supprimé'],$display->invalid);
    }

    /**
     *
     * @return void
     */
    public function testDeletedGroupUser()
    {
        $group = $this->inscription_group;
        // Test user deleted
        $parent = $group->groupe;
        $user   = $parent->user;
        $user->delete();

        $group->load('groupe.user');

        $display = new \App\Droit\Inscription\Entities\Invalid($group);
        $display->trashedUser()->getAdresse();

        $this->assertEquals(['Compte utilisateur ID '.$user->id.' du groupe ID  supprimé'],$display->invalid);
    }

    /**
     *
     * @return void
     */
    public function testUserNotExist()
    {
        $normal = $this->inscription_normal;
        // Test user deleted
        $normal->user_id = 23456;
        $normal->load('user');

        $display = new \App\Droit\Inscription\Entities\Invalid($normal);
        $display->trashedUser()->getAdresse();

        $this->assertEquals(['Aucun utilisateur'],$display->invalid);
    }

    /**
     *
     * @return void
     */
    public function testGroupNotExist()
    {
        $group = $this->inscription_group;

        // Test user deleted
        $group->group_id = 1234;
        $group->load('groupe');

        $display = new \App\Droit\Inscription\Entities\Invalid($group);
        $display->trashedUser()->getAdresse();

        $this->assertEquals(['Aucun groupe'],$display->invalid);
    }

}
