<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttributAdminTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->app['config']->set('database.default','testing');

        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'first_name' => 'Jane',
            'last_name'  => 'Doe',
            'email'      => 'jane.doe@gmail.com',
            'password'   => bcrypt('jane2')
        ]);

        $user->roles()->attach(1);

        $this->user = $user;
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    /**
     * @group attribut_or_reminder
     */
    public function testCreateNewAttribut()
    {
        $this->browse(function (Browser $browser) {

            $browser->loginAs($this->user)->visit(url('admin/attribut'));
            $browser->click('#attribut_create');

            $browser->type('title','Rappel pour le livre');

            $browser->driver->executeScript('$(\'.redactor\').redactor(\'code.set\', \'Dapibus ante suscipurcusit çunc, primiés?\');');

            $browser->radio('reminder',1)
                ->select('duration','week')
                ->press('Envoyer');

            // Calculat the date for 1 week
            $send_at = \Carbon\Carbon::now()->addWeek()->toDateString();

            $this->assertDatabaseHas('shop_attributes', [
                'title'    => 'Rappel pour le livre',
                'reminder' => true,
                'duration' => 'week',
            ]);

        });
    }

    /**
     * @group attribut_or_reminder
     */
    public function testUpdateAttribut()
    {
        $this->browse(function (Browser $browser) {

            $attribute = factory(\App\Droit\Shop\Attribute\Entities\Attribute::class)->create(['title' => 'One title', 'duration' => 'week']);

            $browser->loginAs($this->user)->visit(url('admin/attribut/'.$attribute->id))
                ->type('title','Two title')
                ->press('Envoyer');

            $browser->visit(url('admin/attribut/'.$attribute->id));

            $this->assertDatabaseHas('shop_attributes', [
                'title'    => 'Two title',
                'reminder' => false,
            ]);
        });
    }

    /**
     * @group attribut_or_reminder
     */
    public function testDeleteReminder()
    {
        $this->browse(function (Browser $browser) {

            $attribute = factory(\App\Droit\Shop\Attribute\Entities\Attribute::class)->create([
                'title'    => 'One title',
                'duration' => 'week'
            ]);

            $browser->loginAs($this->user)
                ->visit(url('admin/attribut'))
                ->press('#deleteAttribut_'.$attribute->id);

            $browser->pause(100)->driver->switchTo()->alert()->accept();

            $this->assertDatabaseHas('shop_attributes', [
                'id' => $attribute->id,
                'deleted_at' => null
            ]);
        });
    }

    /**
     * @group attribut_or_reminder
     */
    public function testCreateNewReminder()
    {
        $this->browse(function (Browser $browser) {
            // Make a product
            $make    = new \tests\factories\ObjectFactory();
            $product = $make->product();

            $browser->loginAs($this->user)->visit(url('admin/reminder'))->click('#reminder_product');
            $browser->type('title','Rappel pour le livre');
                $browser->driver->executeScript('$(\'.redactor\').redactor(\'code.set\', \'<p>Dapibus ante suscipurcusit çunc, primiés?</p>\');');
                $browser->select('start','created_at')
                ->select('duration','week')
                ->select('model_id',$product->id)
                ->click('#createReminder');

            // Calculat the date for 1 week
            $send_at = $product->created_at->addWeek();

            $this->assertDatabaseHas('reminders', [
                'send_at'  => $send_at->toDateString(),
                'title'    => 'Rappel pour le livre',
                'model_id' => $product->id,
                'type'     => 'product',
            ]);

        });
    }

    /**
     * @group attribut_or_reminder
     */
    public function testUpdateReminder()
    {
        $this->browse(function (Browser $browser) {

            $make    = new \tests\factories\ObjectFactory();
            $colloque = $make->colloque();

            // Colloque: Default 1 month from start_at test if it's ok
            $send_at = $colloque->start_at->addMonth();

            // Colloque reminder with
            $reminder = factory(\App\Droit\Reminder\Entities\Reminder::class)->create([
                'model_id' => $colloque->id,
                'send_at'  => $send_at->toDateString()
            ]);

            $browser->loginAs($this->user)->visit(url('admin/reminder/'.$reminder->id));

            $this->assertEquals($reminder->send_at->toDateString(), $send_at->toDateString());

            // Update and add 1 week this time
            $browser->type('title','Rappel pour le livre');
            $browser->driver->executeScript('$(\'.redactor\').redactor(\'code.set\', \'<p>Dapibus ante suscipurcusit çunc, primiés?</p>\');');
            $browser->select('duration','week')
                ->select('start','start_at')
                ->select('model_id',$colloque->id)
                ->click('#editReminder');

            $send_at = $colloque->start_at->addWeek();

            $this->assertDatabaseHas('reminders', [
                'id'       => $reminder->id,
                'duration' => 'week',
                'send_at'  => $send_at->toDateString()
            ]);

        });
    }
}
