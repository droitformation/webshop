<?php

namespace Tests\Feature;
use Tests\ResetTbl;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsletterStaticTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $mailjet;
    protected $resources;
    protected $campagne;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $this->mailjet = \Mockery::mock('\Mailjet\Client');
        $this->app->instance('\Mailjet\Client', $this->mailjet);

        $this->resources = \Mockery::mock('\Mailjet\Resources');
        $this->app->instance('\Mailjet\Resources', $this->resources);

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEventDispatchOnCreationStatic()
    {
        $mailjet = new \App\Droit\Newsletter\Worker\MailjetService($this->mailjet,$this->resources);
        $repo = \App::make('App\Droit\Newsletter\Repo\NewsletterInterface');
        $specialisation = factory(\App\Droit\Specialisation\Entities\Specialisation::class)->create();

        \Event::fake();

        $data = [
            'titre'        => 'Newsletter',
            'from_name'    => 'Test',
            'from_email'   => 'testing@test.ch',
            'return_email' => 'testing@test.ch',
            'unsuscribe'   => '/',
            'preview'      => '/',
            'site_id'      => null,
            'list_id'      => 1562017,
            'color'        => '#fff',
            'logos'        => '',
            'header'       => '',
            'static'       => 1,
            'specialisations' => [$specialisation->id]
        ];

        $newsletter = $repo->create($data);

        $newsletter_id = $newsletter->id;

        \Event::assertDispatched(\App\Events\NewsletterStaticCreated::class, function ($e) use ($newsletter_id, $newsletter) {
            return $newsletter->id === $newsletter_id;
        });

        $newsletter = $newsletter->fresh();

        $this->assertEquals([$specialisation->id],$newsletter->specialisations->pluck('id')->toArray());
    }
}
