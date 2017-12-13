<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ListAcceptTest extends BrowserKitTest
{

    use DatabaseTransactions;
    
    public function setUp()
    {
        parent::setUp();
        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class)->create();
        $this->actingAs($user);
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }
    
    public function testIndexPage()
    {
        $this->visit('build/liste')->see('Listes hors campagne');
        $this->assertViewHas('lists');
    }

    public function testListPage()
    {
        $liste = factory(App\Droit\Newsletter\Entities\Newsletter_lists::class)->create([
            'title' => 'One Title'
        ]);

        $email1 = factory(App\Droit\Newsletter\Entities\Newsletter_emails::class)->create();
        $email2 = factory(App\Droit\Newsletter\Entities\Newsletter_emails::class)->create();

        $liste->emails()->saveMany([$email1,$email2]);

        $this->visit('build/liste/'.$liste->id)->see($liste->title);
        $this->assertViewHas('lists');
        $this->assertViewHas('list');
        $this->see('One Title');
    }

    public function testAddSpecialisations()
    {
        $list = App::make('App\Droit\Newsletter\Repo\NewsletterListInterface');
        $make  = new \tests\factories\ObjectFactory();

        $specialisations = $make->items('Specialisation', $nbr = 2);
        $specialisations = $specialisations->pluck('id')->all();

        $result = $list->create(['title' => 'One title', 'emails' => ['cindy.leschaud@gmail.com','pruntrut@yahoo.fr'], 'specialisations' => $specialisations]);

        $this->seeInDatabase('newsletter_lists', ['title' => 'One title']);
        $this->seeInDatabase('newsletter_emails', ['email' => 'cindy.leschaud@gmail.com']);
        $this->seeInDatabase('newsletter_emails', ['email' => 'pruntrut@yahoo.fr']);

        $this->assertEquals(2, $result->emails->count());

        $this->assertEquals($specialisations, $result->specialisations->pluck('id')->all());
    }

    public function testUpdateList()
    {
        $liste = factory(App\Droit\Newsletter\Entities\Newsletter_lists::class)->create([
            'title' => 'One Title'
        ]);

        $make  = new \tests\factories\ObjectFactory();
        $specialisations = $make->items('Specialisation', $nbr = 2);
        $specialisations = $specialisations->pluck('id')->all();

        $this->visit('build/liste/'.$liste->id)->see($liste->title);

        $response = $this->call('PUT','build/liste/'.$liste->id, ['id' =>  $liste->id,'title' => 'Other title', 'specialisations' => $specialisations]);

        $this->visit('build/liste/'.$liste->id)->see('Other title');

        $content = $this->followRedirects()->response->getOriginalContent();
        $content = $content->getData();
        $result  = $content['list'];

        $this->assertEquals($specialisations, $result->specialisations->pluck('id')->all());

        $this->seeInDatabase('newsletter_lists', [
            'id'    => $liste->id,
            'title' => 'Other title'
        ]);
    }

    public function testDeleteList()
    {
        $liste = factory(App\Droit\Newsletter\Entities\Newsletter_lists::class)->create([
            'title' => 'One Title'
        ]);

        $this->visit('build/liste/'.$liste->id)->see($liste->title);

        $response = $this->call('DELETE','build/liste/'.$liste->id);

        $this->notSeeInDatabase('newsletter_lists', [
            'id' => $liste->id,
            'deleted_at' => null
        ]);
    }

    public function testValidEmails()
    {
        $results = collect([['email' => 'cindy.leschaud@gmail.com'],['email' => 'cindy.leschaud@gmail.com'],['email' => 'prundaf.ch']]);
        $emails = $results->pluck('email')
            ->unique()->reject(function ($value, $key) {
                return !filter_var($value, FILTER_VALIDATE_EMAIL) || empty($value);
            });

        $this->assertSame(1,$emails->count());
    }
    
    function prepareFileUpload($path)
    {
        return new \Symfony\Component\HttpFoundation\File\UploadedFile($path, null, \File::mimeType($path), null, null, true);
    }
}
