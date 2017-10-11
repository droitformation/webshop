<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendCampagne;

class CampagneTest extends BrowserKitTest
{
    use WithoutMiddleware, DatabaseTransactions;

    protected $worker;
    protected $mailgun;
    protected $campagne;
    protected $newsletter;
    protected $subscriber;
    protected $response_ok;
    protected $response_fail;

    public function setUp()
    {
        parent::setUp();

        $this->mailgun = Mockery::mock('App\Droit\Newsletter\Worker\MailgunInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\MailgunInterface', $this->mailgun);

        $this->worker = Mockery::mock('App\Droit\Newsletter\Worker\CampagneInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\CampagneInterface', $this->worker);

        $this->campagne = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterCampagneInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterCampagneInterface', $this->campagne);

        $this->newsletter = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterInterface', $this->newsletter);

        $this->subscriber = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterUserInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterUserInterface', $this->subscriber);

        $user = factory(App\Droit\User\Entities\User::class)->create();
        $this->actingAs($user);

        $response = new \stdClass();
        $http_response_body = new \stdClass();

        $response->http_response_code = 200;
        $http_response_body->id       = 123;
        $response->http_response_body = $http_response_body;

        $response_fails = new \stdClass();
        $response_fails->http_response_code = 400;

        $this->response_ok   = $response;
        $this->response_fail = $response_fails;

    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testNewsletterCreationPage()
    {
        // 1x for build page, 1x for new newsletter
        $this->newsletter->shouldReceive('getAll')->twice()->andReturn(collect([]));

        $this->visit('build/newsletter')->click('addNewsletter')->seePageIs('build/newsletter/create');
    }

    public function testSendCampagne()
    {
        Queue::fake();

        $campagne = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create();

        $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
        $this->campagne->shouldReceive('update')->once();
        $this->subscriber->shouldReceive('getByNewsletter')->andReturn(collect(['cindy.leschaud@gmail.com']));
        $this->worker->shouldReceive('html')->once()->andReturn('<html><header></header><body></body></html>');

        $response = $this->call('POST', 'build/send/campagne', ['id' => $campagne->id]);

        Queue::assertPushed(SendCampagne::class, function ($job) use ($campagne) {
            return $job->campagne->id === $campagne->id;
        });

        $this->assertRedirectedTo('build/newsletter');

    }

    public function testSendCampagneFailedHtml()
    {
        try {
            $campagne = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create();

            // some code that is supposed to throw Exception
            $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
            $this->worker->shouldReceive('html')->once()->andReturn('');
            $this->newsletter->shouldReceive('getAll')->once()->andReturn(collect([]));

            $this->visit('build/newsletter');

            $response = $this->call('POST', 'build/send/campagne', ['id' => $campagne->id]);

        } catch (Exception $e) {
            $this->assertType('\App\Exceptions\CampagneUpdateException', $e);
        }
    }

    public function testSendTest()
    {
        $campagne = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();

        $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
        $this->worker->shouldReceive('html')->once()->andReturn('<html><header></header><body></body></html>');
        $this->mailgun->shouldReceive('setSender->setRecipients->setHtml')->once();
        $this->mailgun->shouldReceive('sendTransactional')->once()->andReturn($this->response_ok);

        $response = $this->call('POST', 'build/send/test', ['id' => '1', 'email' => 'cindy.leschaud@gmail.com']);

        $this->assertRedirectedTo('build/campagne/'.$campagne->id);
    }

    public function testSendTestFailed()
    {
        try{
          $campagne = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();

          $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
          $this->worker->shouldReceive('html')->once()->andReturn('<html><header></header><body></body></html>');
          $this->mailgun->shouldReceive('setSender->setRecipients->setHtml')->once();
          $this->mailgun->shouldReceive('sendTransactional')->once()->andReturn($this->response_fail);

          $response = $this->call('POST', 'build/send/test', ['id' => '1', 'email' => 'cindy.leschaud@gmail.com']);

        } catch (Exception $e) {
          $this->assertType('App\Exceptions\NewsletterImplementationException', $e);
        }
    }

    public function testCreateCampagne()
    {
        $campagne   = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->create();
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create();

        $campagne->newsletter = $newsletter;

        $this->campagne->shouldReceive('create')->once()->andReturn($campagne);

        $response = $this->call('POST', 'build/campagne', ['sujet' => 'Sujet', 'auteurs' => 'Cindy Leschaud', 'newsletter_id' => '3']);

        $this->assertRedirectedTo('build/campagne/'.$campagne->id);
    }
}
