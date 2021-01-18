<?php

namespace Tests\Feature\newsletter;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class MailjetServiceTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $mailjet;
    protected $resources;
    protected $campagne;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $this->mailjet = \Mockery::mock('\Mailjet\Client');
        $this->app->instance('\Mailjet\Client', $this->mailjet);

        $this->resources = \Mockery::mock('\Mailjet\Resources');
        $this->app->instance('\Mailjet\Resources', $this->resources);

        $this->campagne = new \App\Droit\Newsletter\Entities\Newsletter_campagnes();
        $this->campagne->sujet = 'Sujet';
        $newsletter = new \App\Droit\Newsletter\Entities\Newsletter();

        $newsletter->from_email = 'droitformation.web@gmail.com';
        $newsletter->from_name  = 'Droit Formation';

        $this->campagne->newsletter = $newsletter;

        $this->app->instance('App\Droit\Newsletter\Entities\Newsletter_campagnes', $this->campagne);

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testSetList()
    {
        $worker = \App::make('App\Droit\Newsletter\Worker\MailjetServiceInterface');

        $worker->setList(1);

        $this->assertEquals(1, 1);
    }


    public function testNoListException()
    {
        $response = \Mockery::mock('\Mailjet\Response');

        //$this->mailjet->shouldReceive('get')->once()->andReturn($response);// called in get
        //$response->shouldReceive('success','getData')->andReturn(true);

        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);

        $this->expectException(\App\Exceptions\ListNotSetException::class);

        $result = $worker->getSubscribers();

        //$errors = $this->app['session.store']->all();
        //$this->assertEquals('Attention aucune liste indiqué',$errors['flash_notification'][0]->message);
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

        $worker->getContactByEmail('droitformation.web@gmail.com');
        $this->assertTrue(true);
    }

    public function testAddEmail()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $this->responseOk([], 'post');

        $worker->addContactToList(1234);
        $this->assertTrue(true);
    }

    public function testAddContactToList()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $this->responseOk([], 'post');

        $worker->addContactToList(1234);
        $this->assertTrue(true);
    }

    public function testRemoveContact()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $return = [0 => ['ID' => 1234]];

        $response = \Mockery::mock('\Mailjet\Response');

        $this->mailjet->shouldReceive('get')->twice()->andReturn($response);// called in getContactByEmail,getListRecipient
        $this->mailjet->shouldReceive('delete')->once()->andReturn($response); // called in removeContact
        $response->shouldReceive('success')->times(3)->andReturn(true); // called in getContactByEmail,getListRecipient,removeContact
        $response->shouldReceive('getData')->times(2)->andReturn($return);// called in getContactByEmail,getListRecipient

        $worker->removeContact('droitformation.web@gmail.com');
        $this->assertTrue(true);
    }

    public function testGetCampagne()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $this->responseOk();

        $worker->getCampagne(1);
        $this->assertTrue(true);
    }

    public function testUpdateCampagne()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $this->responseOk([], 'put');

        $worker->updateCampagne(1,0);
        $this->assertTrue(true);
    }

    public function testCreateCampagne()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $return = [0 => ['ID' => 1234]];

        $this->responseOk($return, 'post');

        $worker->createCampagne($this->campagne);
        $this->assertTrue(true);
    }

    public function testSetHtml()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $this->responseOk([], 'put');

        $worker->setHtml('',1);
        $this->assertTrue(true);
    }

    public function testGetHtml()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $return = [0 => ['Html-part' => 'yeah']];

        $this->responseOk($return);

        $result = $worker->getHtml('',1);

        $this->assertEquals($result,'yeah');
        $this->assertTrue(true);
    }

    public function testSendTest()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);

        $worker->setList(1);

        $this->responseOk([], 'post');

        $worker->sendTest(1,'droitformation.web@gmail.com','title');
        $this->assertTrue(true);
    }

    public function testSendCampagne()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);
        $this->responseOk([], 'post');

        $worker->sendCampagne(1);
        $this->assertTrue(true);
    }

    public function testSendCampagneFailed()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $response = \Mockery::mock('\Mailjet\Response');

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
        $this->assertTrue(true);
    }

    public function testClickStatistics()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $this->responseOk([]);

        $worker->clickStatistics(1);
        $this->assertTrue(true);
    }

    public function testUploadCSVContactslistData()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $return = ['ID' => 123];

        $this->responseOk($return, 'post');

        $worker->uploadCSVContactslistData("Email");
        $this->assertTrue(true);
    }

    public function testImportCSVContactslistData()
    {
        $worker = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $worker->setList(1);

        $this->responseOk([], 'post');

        $worker->importCSVContactslistData(1);

        $this->assertTrue(true);
    }

    public function responseOk($return = [], $type = 'get')
    {
        $response = \Mockery::mock('\Mailjet\Response');

        $this->mailjet->shouldReceive($type)->once()->andReturn($response);
        $response->shouldReceive('success')->once()->andReturn(['success' => true]);
        $response->shouldReceive('getData')->once()->andReturn($return);

        $this->assertTrue(true);
    }
}
