<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CampagneTest extends BrowserKitTest
{
    use WithoutMiddleware, DatabaseTransactions;

    protected $mock;
    protected $worker;
    protected $mailjet;
    protected $campagne;

    public function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock('App\Droit\Newsletter\Service\Mailjet');
        $this->app->instance('App\Droit\Newsletter\Service\Mailjet', $this->mock);

        $this->mailjet = Mockery::mock('App\Droit\Newsletter\Worker\SendgridInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\SendgridInterface', $this->mailjet);

        $this->worker = Mockery::mock('App\Droit\Newsletter\Worker\CampagneInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\CampagneInterface', $this->worker);

        $this->campagne = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterCampagneInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterCampagneInterface', $this->campagne);

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

    /**
     *
     * @return void
     */
    public function testNewsletterCreationPage()
    {
        $this->mailjet->shouldReceive('getAllLists')->once()->andReturn([]);

        $this->visit('build/newsletter')->click('addNewsletter')->seePageIs('build/newsletter/create');
    }

    /**
     *
     * @return void
     */
    public function testSendCampagne()
    {

        $campagne = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();

        $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
        $this->campagne->shouldReceive('update')->once();
        $this->worker->shouldReceive('html')->once()->andReturn('<html><header></header><body></body></html>');

        $this->mailjet->shouldReceive('setHtml')->once()->andReturn(true);
        $this->mailjet->shouldReceive('setList')->once()->andReturn(true);
        $this->mailjet->shouldReceive('sendCampagne')->once()->andReturn(['success' => true]);

        $response = $this->call('POST', 'build/send/campagne', ['id' => '1']);

        $this->assertRedirectedTo('build/newsletter');

    }

    public function testSendCampagneFailedHtml()
    {
        try {
            $campagne = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();
            // some code that is supposed to throw ExceptionOne
            $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
            $this->worker->shouldReceive('html')->once()->andReturn('<html><header></header><body></body></html>');

            $this->mailjet->shouldReceive('setList')->once()->andReturn(true);
            $this->mailjet->shouldReceive('setHtml')->once()->andReturn(false);

            $this->visit('build/newsletter');
            $response = $this->call('POST', 'build/send/campagne', ['id' => '1']);

        } catch (Exception $e) {
            $this->assertType('\designpond\newsletter\Exceptions\CampagneUpdateException', $e);
        }
    }

    public function testSendCampagneFailed()
    {
        try{
            $campagne = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();

            $result = [
                'success' => false,
                'info'    => ['ErrorMessage' => '','StatusCode' => '']
            ];

            $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
            $this->worker->shouldReceive('html')->once()->andReturn('<html><header></header><body></body></html>');

            $this->mailjet->shouldReceive('setList')->once()->andReturn(true);
            $this->mailjet->shouldReceive('setHtml')->once()->andReturn(true);
            $this->mailjet->shouldReceive('sendCampagne')->once()->andReturn($result);

            $response = $this->call('POST', 'build/send/campagne', ['id' => '1']);

        } catch (Exception $e) {
            $this->assertType('designpond\newsletter\Exceptions\CampagneSendException', $e);
        }
    }

    /**
     *
     * @return void
     */
    public function testSendTestCampagne()
    {
     /*   $campagne = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();

        $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
        $this->worker->shouldReceive('html')->once()->andReturn('<html><header></header><body></body></html>');
        $this->mailjet->shouldReceive('sendBulk')->once()->andReturn(['Sent' => []]);

        $response = $this->call('POST', 'build/send/test', ['id' => '1', 'email' => 'cindy.leschaud@gmail.com']);

        $this->assertRedirectedTo('build/campagne/'.$campagne->id);*/

    }

    public function testSendTestFailed()
    {
 /*       try{
            $campagne = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();

            $result = [];

            $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
            $this->worker->shouldReceive('html')->once()->andReturn('<html><header></header><body></body></html>');
            $this->mailjet->shouldReceive('sendBulk')->once()->andReturn($result);

            $this->call('POST', 'build/send/test', ['id' => '1', 'email' => 'cindy.leschaud@gmail.com']);

        } catch (Exception $e) {
            $this->assertType('designpond\newsletter\Exceptions\TestSendException', $e);
        }*/
    }

    /**
     *
     * @return void
     */
    public function testCreateCampagne()
    {
        $campagne   = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->make();

        $campagne->newsletter = $newsletter;

        $this->campagne->shouldReceive('create')->once()->andReturn($campagne);
        $this->campagne->shouldReceive('update')->once()->andReturn($campagne);
        $this->mailjet->shouldReceive('setList')->once();
        $this->mailjet->shouldReceive('createCampagne')->once()->andReturn(1);

        $response = $this->call('POST', 'build/campagne', ['sujet' => 'Sujet', 'auteurs' => 'Cindy Leschaud', 'newsletter_id' => '3']);

        $this->assertRedirectedTo('build/campagne/'.$campagne->id);
    }
}
