<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends BrowserKitTest {

    use DatabaseTransactions;

    protected $adresse;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->mailjet = Mockery::mock('App\Droit\Newsletter\Worker\MailjetServiceInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\MailjetServiceInterface', $this->mailjet);

        DB::beginTransaction();
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }

    public function testCreateNewUser()
    {
         $user = factory(App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        $this->assertTrue(Auth::check());

        $this->visit('admin/user/create');

        // Create new user
        $this->type('Terry', 'first_name');
        $this->type('Jonesy', 'last_name');
        $this->type('terry.jonesy@domain.ch', 'email');
        $this->type('123456', 'password');

        $this->press('Envoyer');

        $this->seeInDatabase('users', [
            'first_name' => 'Terry',
            'last_name' => 'Jonesy',
            'email' => 'terry.jonesy@domain.ch'
        ]);
    }

    public function testCreateNewUserOnlyCompany()
    {
        $user = factory(App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        $this->assertTrue(Auth::check());

        $this->visit('admin/user/create');

        // Create new user
        $this->type('DesignPond', 'company');
        $this->type('info@designpond.ch', 'email');
        $this->type('123456', 'password');

        $this->press('Envoyer');

        $this->seeInDatabase('users', [
            'company' => 'DesignPond',
            'email'   => 'info@designpond.ch'
        ]);
    }

    public function testDeleteThenCreateUserWithSameEmail()
    {
        $admin = factory(App\Droit\User\Entities\User::class)->create();
        $admin->roles()->attach(1);
        $this->actingAs($admin);

        $user = factory(App\Droit\User\Entities\User::class)->create([
            'email' => 'terry.jonesy@domain.ch'
        ]);

        $this->visit('admin/user/'.$user->id);
        // delete user
        $this->click('Supprimer le compte');
        $this->press('Oui Supprimer');

        $this->notSeeInDatabase('users', [
            'id'         => $user->id,
            'deleted_at' => null
        ]);

        $this->visit('admin/user/create');

        // Create new user
        $this->type('Terry', 'first_name');
        $this->type('Jonesy', 'last_name');
        $this->type('terry.jonesy@domain.ch', 'email');
        $this->type('123456', 'password');

        $this->press('Envoyer');

        $this->seeInDatabase('users', [
            'first_name' => 'Terry',
            'last_name'  => 'Jonesy',
            'email'      => 'terry.jonesy@domain.ch'
        ]);
    }

    public function testUpdateUser()
    {
        $user = factory(App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);

        $this->actingAs($user);

        $this->assertTrue(Auth::check());

        $this->visit('admin/user/'.$user->id);
        $this->seePageIs('admin/user/'.$user->id);
        $this->assertViewHas('user');

        $this->type('Terry', 'first_name');

        $this->press('Enregistrer');

        $this->seeInDatabase('users', [
            'id'         => $user->id,
            'first_name' => 'Terry'
        ]);
    }

    public function testUpdateUserOnlyCompany()
    {
        $user = factory(App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);

        $this->actingAs($user);

        $this->assertTrue(Auth::check());

        $this->visit('admin/user/'.$user->id);
        $this->seePageIs('admin/user/'.$user->id);
        $this->assertViewHas('user');

        $this->type('', 'first_name');
        $this->type('', 'last_name');
        $this->type('DesignPond', 'company');

        $this->press('Enregistrer');

        $this->seeInDatabase('users', [
            'id'         => $user->id,
            'first_name' => '',
            'last_name' => '',
            'company' => 'DesignPond'
        ]);
    }

    public function testUserName()
    {
        $user1 = factory(App\Droit\User\Entities\User::class)->create([
            'first_name' => 'Jane',
            'last_name'  => 'Doe',
        ]);

        $user2 = factory(App\Droit\User\Entities\User::class)->create([
            'first_name' => '',
            'last_name'  => '',
            'company'  => 'Acme',
        ]);

        $user3 = factory(App\Droit\User\Entities\User::class)->create([
            'first_name' => 'George',
            'last_name'  => '',
            'company'    => '',
        ]);

        $user4 = factory(App\Droit\User\Entities\User::class)->create([
            'first_name' => '',
            'last_name'  => 'Martin',
            'company'    => '',
        ]);

        $user5 = factory(App\Droit\User\Entities\User::class)->create([
            'first_name' => '',
            'last_name'  => 'Martin',
            'company'    => 'Acme',
        ]);

        $this->assertSame('Jane Doe',$user1->name);
        $this->assertSame('Acme',$user2->name);
        $this->assertSame('George',$user3->name);
        $this->assertSame('Martin',$user4->name);
        $this->assertSame('Acme',$user5->name);
    }

    public function testDeleteUserConfirmation()
    {
        $user   = factory(App\Droit\User\Entities\User::class)->create();
        $person = factory(App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        $site       = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);

        $site2       = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter2 = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 2, 'site_id' => $site2->id]);

        $subscriber = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => $person->email]);

        $subscriber->subscriptions()->attach([$newsletter->id, $newsletter2->id]);

        $this->mailjet->shouldReceive('setList')->once();
        $this->mailjet->shouldReceive('removeContact')->once()->andReturn(true);

        $this->visit('admin/user/confirm/'.$person->id);
        $this->assertViewHas('user');
        $this->see($newsletter->titre);

        $content = $this->response->getOriginalContent();
        $content = $content->getData();

        $this->assertSame([$newsletter->id, $newsletter2->id],$content['user']->email_subscriptions->pluck('subscriptions')->flatten(1)->pluck('id')->all());

        $response = $this->call('DELETE', 'admin/user/'.$person->id, ['newsletter_id' => [$newsletter->id], 'confirm' => 1, 'url' => 'admin/users', 'term' => '']);

        $subscriber->fresh();
        $subscriber->load('subscriptions');

        $this->assertSame(1,$subscriber->subscriptions->count(1));
        $this->assertTrue($subscriber->subscriptions->contains('id',$newsletter2->id));

        $this->seeIsSoftDeletedInDatabase('users', [
            'id' => $person->id
        ]);
    }

    public function testUnsubscribeFromUserPage()
    {
        $user   = factory(App\Droit\User\Entities\User::class)->create();
        $person = factory(App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        // Prepare
        $site       = factory(App\Droit\Site\Entities\Site::class)->create();
        $newsletter = factory(App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1, 'site_id' => $site->id]);

        $subscriber = factory(App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => $person->email]);

        $subscriber->subscriptions()->attach([$newsletter->id]);

        // Assert
        $this->mailjet->shouldReceive('setList')->once();
        $this->mailjet->shouldReceive('removeContact')->once()->andReturn(true);

        $this->visit('admin/user/'.$person->id)->see('désinscrire')->press('désinscrire');

        $subscriber->fresh();
        $subscriber->load('subscriptions');

        $this->assertSame(0,$subscriber->subscriptions->count());
        $this->assertFalse($subscriber->subscriptions->contains('id',$newsletter->id));

    }
}
