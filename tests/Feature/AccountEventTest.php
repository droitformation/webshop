<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AccountEventTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $user = factory(\App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        \DB::beginTransaction();
    }

    public function tearDown()
    {
        \Mockery::close();
        \DB::rollBack();
        \DB::table('users')->truncate();
        \DB::table('adresses')->truncate();
        \DB::table('newsletter_users')->truncate();

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

        $newsletter = factory(\app\droit\newsletter\entities\newsletter::class)->create(['list_id' => 1]);
        $user = factory(\app\droit\newsletter\entities\newsletter_users::class)->create(['email' => $person->email]);
        $user->subscriptions()->attach([$newsletter->id]);

        $old_email = $person->email;
        $new_email = 'new.user@gmail.com';

        $this->assertequals('info@domain.ch',$old_email);

        // user and adresse has the same email
        $this->assertdatabasehas('users', ['email' => $old_email]);
        $this->assertdatabasehas('adresses', ['email' => $old_email]);

        // only the old email exist in the database
        $this->assertdatabasehas('newsletter_users', ['email' => $old_email]);
        $this->assertdatabasemissing('newsletter_users', ['email' => $new_email]);

        // we change the email from the user account
        $response = $this->put('admin/user/'.$person->id, [
            'id'    => $person->id,
            'first_name' => $person->first_name,
            'last_name'  => $person->last_name,
            'email' => $new_email
        ]);

        // the new email exist in the db and the old one is deleted
        $this->assertdatabasehas('newsletter_users', ['email' => $new_email]);
        $this->assertdatabasemissing('newsletter_users', ['email' => $old_email, 'deleted_at' => null]);

        $person = $person->fresh();

        // the new email is set, for user, adresse
        $this->assertequals($person->email,$new_email);
        $this->assertdatabasehas('users', ['email' => $new_email]);
        $this->assertdatabasehas('adresses', ['email' => $new_email]);
        $this->assertdatabasemissing('users', ['email' => $old_email]);
        $this->assertdatabasemissing('adresses', ['email' => $old_email]);

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUpdateAdresse()
    {
        $make = new \tests\factories\ObjectFactory();

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

    }
}
