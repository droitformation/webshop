<?php

namespace Tests\Unit\inscription;

use function GuzzleHttp\Psr7\uri_for;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class InvalidInscriptionTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $colloque;
    protected $inscription_normal;
    protected $inscription_group;
    protected $inscriptions;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
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

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
    public function testDeletedUser()
    {
        $normal = $this->inscription_normal;

        $user = $normal->user;
        // Test user deleted
        $normal->user()->delete();

        $display = new \App\Droit\Inscription\Entities\Invalid($normal);
        $display->trashedUser()->getAdresse();

        $this->assertEquals(['user' => ['message' => 'Compte supprimé', 'id' => $user->id]],$display->invalid);
        $this->assertEquals(url('admin/user/restore/'.$user->id),$display->restoreUrl('user'));

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

        $this->assertEquals(['group_account' => ['message' => 'Compte utilisateur du groupe supprimé', 'id' => $user->id]],$display->invalid);
        $this->assertEquals(url('admin/user/restore/'.$user->id),$display->restoreUrl('group_account'));
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

        $this->assertEquals(['user_missing' => ['message' => 'Aucun utilisateur', 'id' => null]],$display->invalid);
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

        $display = new \App\Droit\Inscription\Entities\Invalid($group);
        $display->trashedUser()->getAdresse();

        $this->assertEquals(['group_missing' => ['message' => 'Aucun groupe', 'id' => null]],$display->invalid);
    }

    /**
     *
     * @return void
     */
    public function testGroupDeleted()
    {
        $group = $this->inscription_group;

        $group_id = $group->groupe->id;

        // Test user deleted
        $group->groupe()->delete();
        $group->load('groupe');

        $display = new \App\Droit\Inscription\Entities\Invalid($group);
        $display->trashedUser()->getAdresse();

        $this->assertEquals(['group_deleted' => ['message' => 'Groupe supprimé', 'id' => $group_id]],$display->invalid);
    }


}
