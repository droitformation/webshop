<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DomainAdminTest extends BrowserKitTest {

    protected $Domain;

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
     * @return void
     */
    public function testIndexDomain()
    {
        $this->visit('admin/domain')->see('Collections');
        $this->assertViewHas('domains');
    }

    public function testDomainCreate()
    {
        $this->visit('admin/domain')->click('addDomain');
        $this->seePageIs('admin/domain/create');

        $this->type('Cindy', 'title')->press('Envoyer');

        $this->seeInDatabase('domains', [
            'title' => 'Cindy'
        ]);
    }

    public function testDomainUpdate()
    {
        $domain = factory(App\Droit\Domain\Entities\Domain::class)->create();

        $this->visit('admin/domain/'.$domain->id);
        $this->type('Webmaster', 'title')->press('Envoyer');

        $this->seeInDatabase('domains', [
            'id'    => $domain->id,
            'title' => 'Webmaster'
        ]);
    }

    public function testDomainDelete()
    {
        $domain = factory(App\Droit\Domain\Entities\Domain::class)->create(['title' => 'New']);

        $this->visit('admin/domain');

        $response = $this->call('DELETE','admin/domain/'.$domain->id);

        $this->notSeeInDatabase('domains', ['id' => $domain->id, 'deleted_at' => null]);
    }
}
