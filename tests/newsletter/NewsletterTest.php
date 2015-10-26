<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewsletterTest extends TestCase
{
    use WithoutMiddleware;

    protected $mock;
    protected $worker;
    protected $mailjet;
    protected $campagne;

    public function setUp()
    {
        parent::setUp();

        $this->mock = Mockery::mock('App\Droit\Newsletter\Service\Mailjet');
        $this->app->instance('App\Droit\Newsletter\Service\Mailjet', $this->mock);

        $this->mailjet = Mockery::mock('App\Droit\Newsletter\Worker\MailjetInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\MailjetInterface', $this->mailjet);

        $this->worker = Mockery::mock('App\Droit\Newsletter\Worker\CampagneInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\CampagneInterface', $this->worker);

        $this->campagne = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterCampagneInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterCampagneInterface', $this->campagne);

        $this->helper = Mockery::mock('App\Droit\Helper\Helper');

        $user = App\Droit\User\Entities\User::find(1);
        $this->be($user);

    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     *
     * @return void
     */
    public function testSubscriptionCreationPage()
    {
        $this->mailjet->shouldReceive('getAllLists')->once()->andReturn([]);

        $this->visit('admin/newsletter')->click('addNewsletter')->seePageIs('admin/newsletter/create');
    }

    /**
     *
     * @return void
     */
    public function testSendCampagne()
    {

        $campagne   = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();

        $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
        $this->worker->shouldReceive('html')->once()->andReturn('<html><header></header><body></body></html>');
        $this->mailjet->shouldReceive('setHtml')->once()->andReturn(true);
        $this->mailjet->shouldReceive('sendCampagne')->once()->andReturn(true);

        $response = $this->call('POST', 'admin/campagne/send', ['id' => '1']);

        $this->assertRedirectedTo('admin/newsletter');

    }

    /**
     *
     * @return void
     */
    public function testSendTestCampagne()
    {
        $campagne   = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();

        $this->campagne->shouldReceive('find')->once()->andReturn($campagne);
        $this->worker->shouldReceive('html')->once()->andReturn('<html><header></header><body></body></html>');
        $this->mailjet->shouldReceive('sendTest')->once()->andReturn(true);

        $response = $this->call('POST', 'admin/campagne/test', ['id' => '1', 'email' => 'cindy.leschaud@gmail.com']);

        $this->assertRedirectedTo('admin/campagne/'.$campagne->id);

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
        $this->mailjet->shouldReceive('setList')->once();
        $this->mailjet->shouldReceive('createCampagne')->once()->andReturn(true);

        $response = $this->call('POST', 'admin/campagne', ['sujet' => 'Sujet', 'auteurs' => 'Cindy Leschaud', 'newsletter_id' => '3']);

        $this->assertRedirectedTo('admin/campagne/'.$campagne->id);

    }

}
