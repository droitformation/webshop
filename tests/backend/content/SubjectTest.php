<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubjectTest extends BrowserKitTest {

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
	public function testSeminaireList()
	{
        $this->visit('admin/seminaire')->see('Seminaires');
        $this->assertViewHas('seminaires');
	}

     public function testSeminaireCreate()
     {
          $product = factory(App\Droit\Shop\Product\Entities\Product::class)->create();

          $this->visit('admin/seminaire')->click('addSeminaire');
          $this->seePageIs('admin/seminaire/create');

          $this->type('Un sujet', 'title')->type('2014', 'year');

          $this->select($product->id, 'product_id');
          $this->attach(public_path('images/avatar.jpg'), 'file');

          $this->press('Créer un seminaire');

          $this->seeInDatabase('seminaires', [
              'title' => 'Un sujet',
              'year' => '2014',
              'product_id' => $product->id,
              'image' => 'avatar.jpg',
          ]);
     }

    public function testUpdateSeminaire()
    {
        $seminaire = factory(App\Droit\Seminaire\Entities\Seminaire::class)->create();

        $subject1 = factory(App\Droit\Seminaire\Entities\Subject::class)->create();
        $subject2 = factory(App\Droit\Seminaire\Entities\Subject::class)->create();

        $seminaire->subjects()->attach([$subject1->id, $subject2->id]);

        $this->visit('admin/seminaire/'.$seminaire->id)->see($seminaire->title);
        $this->see($subject1->title);
        $this->see($subject2->title);

        $this->type('Un autre seminaire', 'title')->press('Envoyer');

        $this->seeInDatabase('seminaires', [
            'id'    => $seminaire->id,
            'title' => 'Un autre seminaire'
        ]);
    }

    public function testDeleteSeminaire()
    {
      $seminaire = factory(App\Droit\Seminaire\Entities\Seminaire::class)->create();

      $this->visit('admin/seminaire/'.$seminaire->id)->see($seminaire->title);

      $response = $this->call('DELETE','admin/seminaire/'.$seminaire->id);

      $this->seeIsSoftDeletedInDatabase('seminaires', [
          'id' => $seminaire->id,
      ]);
    }

    public function testAddNewSubject()
    {
        $seminaire = factory(App\Droit\Seminaire\Entities\Seminaire::class)->create();
        $subject   = factory(App\Droit\Seminaire\Entities\Subject::class)->create();

        $seminaire->subjects()->attach([$subject->id]);

        $this->visit('admin/seminaire/'.$seminaire->id)->see($seminaire->title);
        $this->see($subject->title);

        $response = $this->call('POST','admin/subject', ['title' => 'Un nouveau sujet intéressant', 'seminaire_id' => $seminaire->id, 'rang' => 1234]);

        $this->seeInDatabase('subjects', [
            'title' => 'Un nouveau sujet intéressant',
            'rang' => '1234'
        ]);

        $seminaire->fresh();

        $this->assertEquals(2, $seminaire->subjects->count());
    }

    public function testEditSubject()
    {
        $seminaire = factory(App\Droit\Seminaire\Entities\Seminaire::class)->create();
        $subject   = factory(App\Droit\Seminaire\Entities\Subject::class)->create(['title' => 'Un sujet', 'rang' => 1]);

        $seminaire->subjects()->attach([$subject->id]);

        $this->visit('admin/seminaire/'.$seminaire->id)->see($seminaire->title);
        $this->see($subject->title);

        $response = $this->call('PUT','admin/subject/'.$subject->id,
            ['id' => $subject->id, 'title' => 'Un nouveau sujet intéressant', 'seminaire_id' => $seminaire->id, 'rang' => 1234]
        );

        $this->seeInDatabase('subjects', [
            'id'    => $subject->id,
            'title' => 'Un nouveau sujet intéressant',
            'rang'  => '1234'
        ]);
    }

    public function testDeleteSubject()
    {
        $seminaire = factory(App\Droit\Seminaire\Entities\Seminaire::class)->create();
        $subject   = factory(App\Droit\Seminaire\Entities\Subject::class)->create();

        $seminaire->subjects()->attach([$subject->id]);

        $this->visit('admin/seminaire/'.$seminaire->id)->see($seminaire->title);

        $response = $this->call('DELETE','admin/subject/'.$subject->id);

        $this->seeIsSoftDeletedInDatabase('subjects', [
            'id' => $subject->id,
        ]);

        $seminaire->fresh();

        $this->assertEquals(0, $seminaire->subjects->count());
    }

    /*
     *
/*         $this->assertCount(1, $this->visit('/admin/subject/create')
             ->crawler->filter('input[name="categories"][value="11"]'));

         $this->assertCount(1, $this->visit('/admin/subject/create')
             ->crawler->filter('input[name="authors"][value="71"]'));*/

}
