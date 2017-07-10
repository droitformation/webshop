<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SendListTest extends BrowserKitTest
{
    protected $subscription;
    protected $mailjet;
    protected $newsletter;
    protected $import;
    protected $campagne;
    protected $camp;
    protected $upload;
    protected $excel;

    use WithoutMiddleware, DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->mailjet = Mockery::mock('App\Droit\Newsletter\Worker\MailjetServiceInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\MailjetServiceInterface', $this->mailjet);

        $this->subscription = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterUserInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterUserInterface', $this->subscription);

        $this->newsletter = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterInterface', $this->newsletter);

        $this->campagne = Mockery::mock('App\Droit\Newsletter\Repo\NewsletterCampagneInterface');
        $this->app->instance('App\Droit\Newsletter\Repo\NewsletterCampagneInterface', $this->campagne);

        $this->camp = Mockery::mock('App\Droit\Newsletter\Worker\CampagneInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\CampagneInterface', $this->camp);

        $this->upload = Mockery::mock('App\Droit\Service\UploadInterface');
        $this->app->instance('App\Droit\Service\UploadInterface', $this->upload);

        $this->excel = Mockery::mock('Maatwebsite\Excel\Excel');
        $this->app->instance('Maatwebsite\Excel\Excel', $this->excel);

        $this->import = new \App\Droit\Newsletter\Worker\ImportWorker(
            $this->mailjet,
            $this->subscription,
            $this->newsletter,
            $this->excel,
            $this->campagne,
            $this->camp,
            $this->upload
        );
        
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
    public function testSendListEmail()
    {
        // Prepare list of emails
        $campagne = factory(App\Droit\Newsletter\Entities\Newsletter_campagnes::class)->make();
        $html = '<html><head></head><body></body></html>';

        $this->camp->shouldReceive('html')->once()->andReturn($html);
        $this->campagne->shouldReceive('find')->once()->andReturn($campagne);

        $liste = factory(App\Droit\Newsletter\Entities\Newsletter_lists::class)->create();
        $emails = factory(App\Droit\Newsletter\Entities\Newsletter_emails::class, 230)->make();

        foreach ($emails as $email){
            $liste->emails()->save($email);
        }

        // Send list of emailsby chunk, 100 at the time 230/100 => rounded to 3 times
        $this->mailjet->shouldReceive('sendBulk')->times(3)->andReturn(true);

        $this->import->send(1,$liste);
    }

}
