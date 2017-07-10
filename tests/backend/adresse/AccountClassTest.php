<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AccountClassTest extends BrowserKitTest {

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $user = factory(App\Droit\User\Entities\User::class)->create();

        $user->roles()->attach(1);
        $this->actingAs($user);

        DB::beginTransaction();
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }

    public function testAccountModelUserName()
    {
        $make    = new \tests\factories\ObjectFactory();
        $user    = $make->makeUser();
        $adresse = $user->adresses->first();

        $account = new App\Droit\Adresse\Entities\Account($user);

        $this->assertSame($adresse->name ,$account->name);
    }

    public function testAccountModelAdresseName()
    {
        $make    = new \tests\factories\ObjectFactory();

        $adresse = $make->adresse();

        $account = new App\Droit\Adresse\Entities\Account($adresse);

        $this->assertSame($adresse->name ,$account->name);
    }

    public function testAccountModelUserMainAdresse()
    {
        $make    = new \tests\factories\ObjectFactory();
        $user    = $make->makeUser();
        $adresse = $user->adresses->first();

        $account = new App\Droit\Adresse\Entities\Account($user);

        $before = $user->adresses->pluck('id')->all();
        $after  = $account->adresses->pluck('id')->all();

        $this->assertSame($adresse->id ,$account->main_adresse->id);
        $this->assertSame($before,$after);
        $this->assertFalse($account->user_deleted);
        $this->assertFalse($account->main_adresse_deleted);
    }

    public function testAccountModelAdresseMainAdresse()
    {
        $make    = new \tests\factories\ObjectFactory();
        $adresse = $make->adresse();

        $account = new App\Droit\Adresse\Entities\Account($adresse);

        $this->assertSame($adresse->id ,$account->main_adresse->id);
        $this->assertFalse($account->main_adresse_deleted);
    }

    public function testAccountModelUserDeleted()
    {
        $make    = new \tests\factories\ObjectFactory();
        $user    = $make->makeUser();
        $adresse = $user->adresses->first();

        $user->delete();

        $account = new App\Droit\Adresse\Entities\Account($user);

        $this->assertSame($adresse->name ,$account->name);
        $this->assertTrue($account->user_deleted);
        $this->assertFalse($account->main_adresse_deleted);
    }

    public function testAccountModelUserAdresseDeleted()
    {
        $make    = new \tests\factories\ObjectFactory();
        $user    = $make->makeUser();
        $adresse = $user->adresses->first();
        $adresse->delete();

        $account = new App\Droit\Adresse\Entities\Account($user);

        $this->assertSame($adresse->name ,$account->name);
        $this->assertFalse($account->user_deleted);
        $this->assertTrue($account->main_adresse_deleted);
    }


    public function testAccountModelUserDeletedAdresseDeleted()
    {
        $make    = new \tests\factories\ObjectFactory();
        $user    = $make->makeUser();
        $adresse = $user->adresses->first();

        $user->delete();
        $adresse->delete();

        $account = new App\Droit\Adresse\Entities\Account($user);

        $this->assertSame($adresse->name ,$account->name);
        $this->assertTrue($account->user_deleted);
        $this->assertTrue($account->main_adresse_deleted);
    }

    public function testAccountModelAdresseDeleted()
    {
        $make    = new \tests\factories\ObjectFactory();

        $adresse = $make->adresse();
        $adresse->delete();
        $adresse->fresh();

        $account = new App\Droit\Adresse\Entities\Account($adresse);

        $this->assertSame($adresse->name ,$account->name);
        $this->assertTrue($account->main_adresse_deleted);
    }
}
