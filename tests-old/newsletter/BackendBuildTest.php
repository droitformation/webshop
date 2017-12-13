<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BackendBuildTest extends BrowserKitTest
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
        $this->visit('build/newsletter')->see('Liste des newsletter');

        $this->assertViewHas('listes'); // => no subscriptions list
        $this->assertViewHas('newsletters');
    }

    public function testArchives()
    {
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create([
            'titre'        => 'New Newsletter',
            'list_id'      => '1',
            'from_name'    => 'Cindy Leschaud',
            'from_email'   => 'cindy.leschaud@gmail.com',
        ]);

        $campagne = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create([
            'sujet'           => 'Sujet',
            'auteurs'         => 'Cindy Leschaud',
            'status'          => 'Envoyé',
            'send_at'         => \Carbon\Carbon::createFromDate(2016, 12, 22)->toDateTimeString(),
            'newsletter_id'   => $newsletter->id,
            'api_campagne_id' => 1,
            'created_at'      => \Carbon\Carbon::createFromDate(2016, 12, 21)->toDateTimeString(),
            'updated_at'       => \Carbon\Carbon::createFromDate(2016, 12, 21)->toDateTimeString(),
        ]);

        $this->visit('build/newsletter/archive/'.$newsletter->id.'/2016')->see('Archive');

        $content = $this->response->getOriginalContent();
        $content = $content->getData();

        $campagnes = $content['campagnes'];

        $this->assertEquals(1,$campagnes->count());

    }

    public function testUpdateCampagneInfo()
    {
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create();

        $campagne = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create([
            'sujet'           => 'Sujet',
            'auteurs'         => 'Cindy Leschaud',
            'newsletter_id'   => $newsletter->id,
        ]);

        $this->visit('build/campagne/'.$campagne->id.'/edit')->see($campagne->sujet);

        $this->type('Dapibus ante çunc, primiés?', 'sujet')
            ->press('Éditer');

        $this->followRedirects()
            ->visit('build/campagne/'.$campagne->id.'/edit')
            ->see('Dapibus ante çunc, primiés?');
    }

    public function testCampagneCompose()
    {
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create();

        $campagne = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create([
            'sujet'           => 'Sujet',
            'auteurs'         => 'Cindy Leschaud',
            'newsletter_id'   => $newsletter->id,
        ]);

        $content1 = factory(App\Droit\Newsletter\Entities\Newsletter_contents::class)->create([
            'type_id'  => 6, // text
            'titre'    => 'Lorem ipsum dolor amet',
            'contenu'  => 'Lorem ad quîs j\'libéro pharétra vivamus mounc!',
            'newsletter_campagne_id' => $campagne->id,
        ]);

        $content2 = factory(App\Droit\Newsletter\Entities\Newsletter_contents::class)->create([
            'type_id'  => 6, // text
            'titre'    => 'Lorem ipsum dolor amet',
            'contenu'  => 'Convallis èiam condimentum lacinia vulputaté ïn metus litora sit vulputaté vélit, consequat liçlà.',
            'newsletter_campagne_id' => $campagne->id,
        ]);

        $this->visit('build/campagne/'.$campagne->id)
            ->see($content1->titre)
            ->see($content2->titre);
    }

    public function testPastCampagneCannotBeSendForTest()
    {

        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create();

        $campagne = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create([
            'sujet'           => 'Sujet',
            'auteurs'         => 'Cindy Leschaud',
            'status'          => 'Envoyé',
            'send_at'         => \Carbon\Carbon::createFromDate(2016, 12, 22)->toDateTimeString(),
            'newsletter_id'   => $newsletter->id,
            'api_campagne_id' => 1,
            'created_at'      => \Carbon\Carbon::createFromDate(2016, 12, 21)->toDateTimeString(),
            'updated_at'      => \Carbon\Carbon::createFromDate(2016, 12, 21)->toDateTimeString(),
        ]);

        $this->visit('build/campagne/'.$campagne->id)->dontSee('Envoyer un test');
    }

    function prepareFileUpload($path)
    {
        return new \Symfony\Component\HttpFoundation\File\UploadedFile($path, null, \File::mimeType($path), null, null, true);
    }
}
