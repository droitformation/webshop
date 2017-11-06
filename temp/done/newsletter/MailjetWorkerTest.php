<?php

class MailjetWorkerTest extends BrowserKitTest
{
    protected $mailjet;
    protected $resources;
    protected $campagne;

    public function setUp()
    {
        parent::setUp();

        $this->mailjet = Mockery::mock('\Mailjet\Client');
        $this->app->instance('\Mailjet\Client', $this->mailjet);

        $this->resources = Mockery::mock('\Mailjet\Resources');
        $this->app->instance('\Mailjet\Resources', $this->resources);

        $this->campagne = new \App\Droit\Newsletter\Entities\Newsletter_campagnes();
        $this->campagne->sujet = 'Sujet';
        $newsletter = new \App\Droit\Newsletter\Entities\Newsletter();

        $newsletter->from_email = 'cindy.leschaud@gmail.com';
        $newsletter->from_name  = 'Cindy Leschaud';

        $this->campagne->newsletter = $newsletter;

        $this->app->instance('App\Droit\Newsletter\Entities\Newsletter_campagnes', $this->campagne);
    }

    public function tearDown()
    {
        Mockery::close();
    }
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testSetList()
    {
        $worker = \App::make('App\Droit\Newsletter\Worker\MailjetServiceInterface');

        $worker->setList(1);

        $this->assertEquals(1, 1);
    }

    /**
     * @expectedException App\Exceptions\ListNotSetException
     */
    public function testNoListException()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $result = $worker->getSubscribers();
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testGetResponses()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $normalReponse = [
            'getAllLists',
            'getSubscribers',
            'getAllSubscribers',
        ];

        foreach($normalReponse as $call)
        {
            $this->responseOk();

            $worker->$call();
        }
    }

    public function testGetByEmail()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $return = [0 => ['ID' => 1234]];

        $this->responseOk($return);

        $worker->getContactByEmail('cindy.leschaud@gmail.com');
    }

    public function testAddEmail()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $this->responseOk([], 'post');

        $worker->addContactToList(1234);
    }

    public function testAddContactToList()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $this->responseOk([], 'post');

        $worker->addContactToList(1234);
    }

    public function testRemoveContact()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $return = [0 => ['ID' => 1234]];

        $response = Mockery::mock('\Mailjet\Response');

        $this->mailjet->shouldReceive('get')->twice()->andReturn($response);// called in getContactByEmail,getListRecipient
        $this->mailjet->shouldReceive('delete')->once()->andReturn($response); // called in removeContact
        $response->shouldReceive('success')->times(3)->andReturn(true); // called in getContactByEmail,getListRecipient,removeContact
        $response->shouldReceive('getData')->times(2)->andReturn($return);// called in getContactByEmail,getListRecipient

        $worker->removeContact('cindy.leschaud@gmail.com');
    }

    public function testGetCampagne()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $this->responseOk();

        $worker->getCampagne(1);
    }

    public function testUpdateCampagne()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $this->responseOk([], 'put');

        $worker->updateCampagne(1,0);
    }

    public function testCreateCampagne()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $return = [0 => ['ID' => 1234]];

        $this->responseOk($return, 'post');

        $worker->createCampagne($this->campagne);
    }

    public function testSetHtml()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $this->responseOk([], 'put');

        $worker->setHtml('',1);
    }

    public function testGetHtml()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $return = [0 => ['Html-part' => 'yeah']];

        $this->responseOk($return);

        $result = $worker->getHtml('',1);

        $this->assertEquals($result,'yeah');
    }

    public function testSendTest()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);

        $worker->setList(1);

        $this->responseOk([], 'post');

        $worker->sendTest(1,'cindy.leschaud@gmail.com','title');
    }

    public function testSendCampagne()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);
        $this->responseOk([], 'post');

        $worker->sendCampagne(1);
    }

    public function testSendCampagneFailed()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $response = Mockery::mock('\Mailjet\Response');

        $this->mailjet->shouldReceive('post')->once()->andReturn($response);
        $response->shouldReceive('success')->once()->andReturn(false);
        $response->shouldReceive('getData')->once()->andReturn([]);

        $result = $worker->sendCampagne(1);

        $this->assertFalse($result['success']);
    }

    public function testStatsCampagne()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $return = [0 => 123];

        $this->responseOk($return);

        $worker->statsCampagne(1);
    }

    public function testClickStatistics()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $this->responseOk([]);

        $worker->clickStatistics(1);
    }

    public function testUploadCSVContactslistData()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $return = ['ID' => 123];

        $this->responseOk($return, 'post');

        $worker->uploadCSVContactslistData("Email");
    }

    public function testImportCSVContactslistData()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $this->responseOk([], 'post');

        $worker->importCSVContactslistData(1);
    }

    public function responseOk($return = [], $type = 'get')
    {
        $response = Mockery::mock('\Mailjet\Response');

        $this->mailjet->shouldReceive($type)->once()->andReturn($response);
        $response->shouldReceive('success')->once()->andReturn(['success' => true]);
        $response->shouldReceive('getData')->once()->andReturn($return);
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
