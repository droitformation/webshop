<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\BrowserKitTesting\DatabaseTransactions;

class AuthorTest extends BrowserKitTest {

    protected $author;

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
    public function testAuthorList()
    {
        $this->visit('admin/author');
        $this->assertViewHas('authors');
    }

    public function testAuthorCreate()
    {
        $this->visit('admin/author')->click('addAuthor');
        $this->seePageIs('admin/author/create');

        $this->type('Cindy', 'first_name')
            ->type('Leschaud', 'last_name')
            ->type('Webmaster', 'occupation')
            ->type('Une bio', 'bio')
            ->press('Envoyer');

        $this->seeInDatabase('authors', [
            'first_name' => 'Cindy',
            'last_name'  => 'Leschaud',
            'occupation' => 'Webmaster',
            'bio'        => 'Une bio'
        ]);
    }

    public function testAuthorUpdate()
    {
        $author = factory(App\Droit\Author\Entities\Author::class)->create();

        $this->visit('admin/author/'.$author->id);
        $this->type('Webmaster', 'occupation')->press('Envoyer');

        $this->seeInDatabase('authors', [
            'first_name' => $author->first_name,
            'last_name'  => $author->last_name,
            'occupation' => 'Webmaster',
            'bio'        => $author->bio
        ]);
    }

    public function testAuthorDelete()
    {
        $author = factory(App\Droit\Author\Entities\Author::class)->create();

        $this->visit('admin/author');

        $response = $this->call('DELETE','admin/author/'.$author->id);

        $this->notSeeInDatabase('authors', ['id' => $author->id]);
    }
}
