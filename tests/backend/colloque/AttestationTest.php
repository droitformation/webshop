<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AttestationTest extends TestCase {

    use DatabaseTransactions;

    protected $colloque;

    public function setUp()
    {
        parent::setUp();
        DB::beginTransaction();

        $user = factory(App\Droit\User\Entities\User::class,'admin')->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }

    public function testCreateAttestationForColloque()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();

        $this->visit('admin/attestation/colloque/'.$colloque->id);

        $this->type('Une attestation', 'title');
        $this->type('Bienne', 'lieu');
        $this->type('DesignPond', 'organisateur');
        $this->type('078 690 12 34', 'telephone');
        $this->type('Cindy Leschaud', 'signature');
        $this->type('<div>Un commentaire</div>', 'comment');
        $this->press('Envoyer');

        $this->seeInDatabase('colloque_attestations', [
            'colloque_id'  => $colloque->id,
            'telephone'    => '078 690 12 34',
            'lieu'         => 'Bienne',
            'organisateur' => 'DesignPond',
            'title'        => 'Une attestation',
            'signature'    => 'Cindy Leschaud',
            'comment'      => '<div>Un commentaire</div>',
        ]);
    }

    public function testUpdateAttestationForColloque()
    {
        $make        = new \tests\factories\ObjectFactory();
        $colloque    = $make->colloque();
        $attestation = factory(App\Droit\Colloque\Entities\Colloque_attestation::class)->create([
            'colloque_id'  => $colloque->id,
        ]);

        $this->visit('admin/attestation/'.$attestation->id);

        $this->type('Une autre attestation', 'title');
        $this->type('Neuchâtel', 'lieu');
        $this->type('DesignPond', 'organisateur');
        $this->type('078 690 12 34', 'telephone');
        $this->type('Jane Doe', 'signature');
        $this->type('<div>Un autre commentaire</div>', 'comment');
        $this->press('Envoyer');

        $this->seeInDatabase('colloque_attestations', [
            'colloque_id'  => $colloque->id,
            'title'        => 'Une autre attestation',
            'organisateur' => 'DesignPond',
            'lieu'         => 'Neuchâtel',
            'telephone'    => '078 690 12 34',
            'signature'    => 'Jane Doe',
            'comment'      => '<div>Un autre commentaire</div>',
        ]);
    }
}
