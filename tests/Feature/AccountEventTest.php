<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AccountEventTest extends BrowserKitTest
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        DB::beginTransaction();

        $user = factory(\App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

    }

    public function tearDown()
    {
        \Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testupdateuser()
    {
        $make = new \tests\factories\objectfactory();
        $person = $make->makeuser(['email' => 'info@domain.ch','first_name' => 'cindy', 'last_name' => 'leschaud']);

        $newsletter = factory(\App\Droit\Newsletter\Entities\Newsletter::class)->create(['list_id' => 1]);
        $user = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create(['email' => $person->email]);
        $user->subscriptions()->attach([$newsletter->id]);

        $old_email = $person->email;
        $new_email = 'new.user@gmail.com';

        $this->assertequals('info@domain.ch',$old_email);

        // user and adresse has the same email
        $this->seeInDatabase('users', ['email' => $old_email]);
        $this->seeInDatabase('adresses', ['email' => $old_email]);

        // only the old email exist in the database
        $this->seeInDatabase('newsletter_users', ['email' => $old_email]);
        $this->dontSeeInDatabase('newsletter_users', ['email' => $new_email]);

        // we change the email from the user account
        $response = $this->put('admin/user/'.$person->id, [
            'id'    => $person->id,
            'first_name' => $person->first_name,
            'last_name'  => $person->last_name,
            'email' => $new_email
        ]);

        // the new email exist in the db and the old one is deleted
        $this->seeInDatabase('newsletter_users', ['email' => $new_email]);
        $this->dontSeeInDatabase('newsletter_users', ['email' => $old_email, 'deleted_at' => null]);

        $person = $person->fresh();

        // the new email is set, for user, adresse
        $this->assertequals($person->email,$new_email);
        $this->seeInDatabase('users', ['email' => $new_email]);
        $this->seeInDatabase('adresses', ['email' => $new_email]);
        $this->dontSeeInDatabase('users', ['email' => $old_email]);
        $this->dontSeeInDatabase('adresses', ['email' => $old_email]);

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
        $this->seeInDatabase('users', ['email' => $person->email]);
        $this->seeInDatabase('adresses', ['email' => $adresse->email]);

        // only the old email exist in the database
        $this->seeInDatabase('newsletter_users', ['email' => $person->email]);
        $this->dontSeeInDatabase('newsletter_users', ['email' => $new_email]);

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
        $this->seeInDatabase('newsletter_users', ['email' => $new_email]);
        $this->dontSeeInDatabase('newsletter_users', ['email' => $old_email_u, 'deleted_at' => null]);

        $person = $person->fresh();

        // the new email is set, for user, adresse
        $this->assertEquals($person->email,$new_email);
        $this->seeInDatabase('users', ['email' => $new_email]);
        $this->seeInDatabase('adresses', ['email' => $new_email]);
        $this->dontSeeInDatabase('users', ['email' => $old_email_u]);
        $this->dontSeeInDatabase('adresses', ['email' => $old_email_a]);

    }
}
