<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminAdresseTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * @group adresse_or_user
     */
    public function testCreateAdresseForUserForm()
    {
        \DB::table('adresses')->truncate();
        \DB::table('users')->truncate();

        $this->browse(function (Browser $browser) {

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $person  = factory(\App\Droit\User\Entities\User::class)->create();

            $browser->loginAs($user)->visit(url('admin/adresse/create/'.$person->id));

            // Create adresse with user data but change last_name
            $browser->type('first_name',$person->first_name);
            $browser->type('last_name','Jones');
            $browser->type('email',$person->email);
            $browser->type('adresse','Rue du test 23');
            $browser->type('npa',1234);
            $browser->type('ville','Bienne');
            $browser->click('#createAdresse');

            $browser->visit(url('admin'));

            $this->assertDatabaseHas('adresses', [
                'user_id'    => $person->id,
                'first_name' => $person->first_name,
                'last_name'  => 'Jones',
                'email'      => $person->email,
                'adresse'    => 'Rue du test 23',
                'npa'        => '1234',
                'ville'      => 'Bienne',
            ]);

        });
    }

    /**
     * @group adresse_or_user
     */
    public function testNewAdresse()
    {
        \DB::table('adresses')->truncate();

        $this->browse(function (Browser $browser) {

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $browser->loginAs($user)->visit(url('admin/adresse/create'));

            $browser->type('first_name','Terry');
            $browser->type('last_name','Jones');
            $browser->type('email','terry.jones@gmail.com');
            $browser->type('adresse','Rue du test 23');
            $browser->type('npa',1234);
            $browser->type('ville','Bienne');
            $browser->press('#createAdresse');

            $browser->visit(url('admin'));

            $this->assertDatabaseHas('adresses', [
                'first_name' => 'Terry',
                'last_name'  => 'Jones',
                'email'      => 'terry.jones@gmail.com',
                'adresse'    => 'Rue du test 23',
                'npa'        => '1234',
                'ville'      => 'Bienne',
            ]);
        });
    }

    /**
     * @group adresse_or_user
     */
    public function testNewUser()
    {
        $this->browse(function (Browser $browser) {

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $browser->loginAs($user)->visit(url('admin/user/create'));

            $browser->type('first_name','Terry');
            $browser->type('last_name','Jones');
            $browser->type('email','terry.jones@gmail.com');
            $browser->type('password','123456');
            $browser->press('#createUser');

            $this->assertDatabaseHas('users', [
                'first_name' => 'Terry',
                'last_name' => 'Jones',
                'email' => 'terry.jones@gmail.com'
            ]);
        });
    }

    /**
     * @group adresse_or_user
     */
    public function testNewUserOnlyCompany()
    {
        $this->browse(function (Browser $browser) {

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $browser->loginAs($user)->visit(url('admin/user/create'));

            $browser->type('company','DesignPond');
            $browser->type('email','info@designpond.ch');
            $browser->type('password','123456');
            $browser->press('#createUser');

            $this->assertDatabaseHas('users', [
                'company' => 'DesignPond',
                'email'   => 'info@designpond.ch'
            ]);
        });
    }

    /**
     * @group adresse_or_user
     */
    public function testDeleteThenCreateUserWithSameEmail()
    {
        $this->browse(function (Browser $browser) {

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $person = factory(\App\Droit\User\Entities\User::class)->create([
                'email' => 'jane.doe@gmail.com'
            ]);

            $browser->loginAs($user)->visit(url('admin/user/'.$person->id));
            $browser->press('#deleteUser_'.$person->id);

            $browser->driver->switchTo()->alert()->accept();
            $browser->visit(url('admin/user/'.$person->id));

            $this->assertDatabaseMissing('users', [
                'id'         => $person->id,
                'deleted_at' => null
            ]);

            $browser->visit(url('admin/user/create'));

            $browser->type('first_name','Jane');
            $browser->type('last_name','Doe');
            $browser->type('email','jane.doe@gmail.com');
            $browser->type('password','123456');
            $browser->press('#createUser');

            $this->assertDatabaseHas('users', [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'jane.doe@gmail.com'
            ]);
        });
    }

    /**
     * @group adresse_or_user
     */
    public function testUpdateUser()
    {
        $this->browse(function (Browser $browser) {

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $person = factory(\App\Droit\User\Entities\User::class)->create([
                'email' => 'terry.jonesy@domain.ch'
            ]);

            $browser->loginAs($user)->visit(url('admin/user/'.$person->id));

            $browser->type('first_name','Terry');
            $browser->press('#updateUser');

            $this->assertDatabaseHas('users', [
                'first_name' => 'Terry',
                'email'      => $person->email
            ]);

        });
    }

    /**
     * @group adresse_or_user
     */
    public function testUpdateUserOnlyCompany()
    {
        $this->browse(function (Browser $browser) {

            $user = factory(\App\Droit\User\Entities\User::class)->create();
            $user->roles()->attach(1);

            $person = factory(\App\Droit\User\Entities\User::class)->create([
                'email' => 'terry.jonesy@domain.ch'
            ]);

            $browser->loginAs($user)->visit(url('admin/user/'.$person->id));

            $browser->type('first_name','');
            $browser->type('last_name','');
            $browser->type('company','DesignPond');
            $browser->press('#updateUser');

            $this->assertDatabaseHas('users', [
                'company'    => 'DesignPond',
                'first_name' => '',
                'last_name'  => '',
                'email'      => $person->email
            ]);

        });
    }
}
