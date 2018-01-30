<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class AccountEventTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $mailjet;

    public function setUp()
    {
        parent::setUp();
        $this->app['config']->set('database.default','testing');
        $this->reset_all();
     
        $user = factory(\App\Droit\User\Entities\User::class)->create();

        $this->mailjet = \Mockery::mock('App\Droit\Newsletter\Worker\MailjetServiceInterface');
        $this->app->instance('App\Droit\Newsletter\Worker\MailjetServiceInterface', $this->mailjet);

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
    public function testUpdateUserKeepPassword()
    {
        $make = new \tests\factories\Objectfactory();

        $person = factory(\App\Droit\User\Entities\User::class)->create([
            'email'      => 'info@domain.ch',
            'first_name' => 'cindy',
            'last_name'  => 'leschaud',
            'password'   => bcrypt('secret')
        ]);

        $new_email = 'new.user@gmail.com';

        // we change the email from the user account
        $response = $this->put('admin/user/'.$person->id, [
            'id'    => $person->id,
            'first_name' => $person->first_name,
            'last_name'  => $person->last_name,
            'email' => $new_email
        ]);

        $person = $person->fresh();

        // the new email is set, for user, adresse
        $this->assertequals($person->email,$new_email);
        $this->assertNotEmpty($person->password);

        \DB::table('newsletters')->truncate();
        \DB::table('newsletter_users')->truncate();
        \DB::table('users')->truncate();
        \DB::table('adresses')->truncate();
    }

    public function testUpdateUser()
    {
        $make = new \tests\factories\ObjectFactory();
        $person = $make->makeUser(['email' => 'info@domain.ch','first_name' => 'cindy', 'last_name' => 'leschaud']);

        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1]);
        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => $person->email]);
        $user->subscriptions()->attach([$newsletter->id]);

        $old_email = $person->email;
        $new_email = 'new.user@gmail.com';

        $this->assertequals('info@domain.ch',$old_email);

        // user and adresse has the same email
        $this->assertDatabaseHas('users', ['email' => $old_email]);
        $this->assertDatabaseHas('adresses', ['email' => $old_email]);

        // only the old email exist in the database
        $this->assertDatabaseHas('newsletter_users', ['email' => $old_email]);
        $this->assertDatabaseMissing('newsletter_users', ['email' => $new_email]);

        $this->mailjet->shouldReceive('setList')->times(2);
        $this->mailjet->shouldReceive('removeContact')->times(1)->andReturn(true);
        $this->mailjet->shouldReceive('subscribeEmailToList')->times(1)->andReturn(true);

        // we change the email from the user account
        $response = $this->put('admin/user/'.$person->id, [
            'id'    => $person->id,
            'first_name' => $person->first_name,
            'last_name'  => $person->last_name,
            'email' => $new_email
        ]);

        // the new email exist in the db and the old one is deleted
        $this->assertDatabaseHas('newsletter_users', ['email' => $new_email]);
        $this->assertDatabaseMissing('newsletter_users', ['email' => $old_email, 'deleted_at' => null]);

        $person = $person->fresh();

        // the new email is set, for user, adresse
        $this->assertequals($person->email,$new_email);
        $this->assertDatabaseHas('users', ['email' => $new_email]);
        $this->assertDatabaseHas('adresses', ['email' => $new_email]);
        $this->assertDatabaseMissing('users', ['email' => $old_email]);
        $this->assertDatabaseMissing('adresses', ['email' => $old_email]);

        \DB::table('newsletters')->truncate();
        \DB::table('newsletter_users')->truncate();
        \DB::table('users')->truncate();
        \DB::table('adresses')->truncate();

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUpdateAdresse()
    {
        $person = factory(\App\Droit\User\Entities\User::class)->create(['email' => 'info@domain.ch']);
        $adresse = factory(\App\Droit\Adresse\Entities\Adresse::class)->create(['email' => 'old_adresse@gmail.com','user_id' => $person->id]);

        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1]);
        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => $person->email]);
        $user->subscriptions()->attach([$newsletter->id]);

        $old_email_a = $adresse->email;
        $old_email_u = $person->email;

        $new_email = 'new.user@gmail.com';

        // user and adresse don't have the same email
        $this->assertDatabaseHas('users', ['email' => $person->email]);
        $this->assertDatabaseHas('adresses', ['email' => $adresse->email]);

        // only the old email exist in the database
        $this->assertDatabaseHas('newsletter_users', ['email' => $person->email]);
        $this->assertDatabaseMissing('newsletter_users', ['email' => $new_email]);

        $this->mailjet->shouldReceive('setList')->times(2);
        $this->mailjet->shouldReceive('removeContact')->times(1)->andReturn(true);
        $this->mailjet->shouldReceive('subscribeEmailToList')->times(1)->andReturn(true);

        // We change the email from the adresse account
        $response = $this->put('admin/adresse/'.$adresse->id, [
            'id'            => $adresse->id,
            'email'         => $new_email,
            'first_name'    => $adresse->first_name,
            'last_name'     => $adresse->last_name,
            'adresse'       => $adresse->adresse,
            'npa'           => $adresse->npa,
            'ville'         => $adresse->ville,
        ]);

        // the new email exist in the db and the old one is deleted
        $this->assertDatabaseHas('newsletter_users', ['email' => $new_email]);
        $this->assertDatabaseMissing('newsletter_users', ['email' => $old_email_u, 'deleted_at' => null]);

        $person = $person->fresh();

        // the new email is set, for user, adresse
        $this->assertEquals($person->email,$new_email);
        $this->assertDatabaseHas('users', ['email' => $new_email]);
        $this->assertDatabaseHas('adresses', ['email' => $new_email]);
        $this->assertDatabaseMissing('users', ['email' => $old_email_u]);
        $this->assertDatabaseMissing('adresses', ['email' => $old_email_a]);

        \DB::table('newsletters')->truncate();
        \DB::table('newsletter_users')->truncate();
        \DB::table('users')->truncate();
        \DB::table('adresses')->truncate();
    }


}
