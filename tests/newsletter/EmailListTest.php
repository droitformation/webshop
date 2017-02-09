<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmailListTest extends BrowserKitTest
{
    use DatabaseTransactions;
    
    public function setUp()
    {
        parent::setUp();
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

    public function testAddEmailToList()
    {
        $liste = factory(App\Droit\Newsletter\Entities\Newsletter_lists::class)->create([
            'title' => 'One Title'
        ]);

        $response = $this->call('POST', 'build/emails', ['list_id' => $liste->id, 'email' => 'cindy.leschaud@gmail.com']);

        $this->seeInDatabase('newsletter_emails', [
            'list_id'  => $liste->id,
            'email'    => 'cindy.leschaud@gmail.com'
        ]);

        // Add same email
        $response = $this->call('POST', 'build/emails', ['list_id' => $liste->id, 'email' => 'cindy.leschaud@gmail.com']);

        $liste->load('emails');

        $this->assertEquals(1, $liste->emails->count());
    }

    public function testDeleteEmailFromList()
    {
        $liste = factory(App\Droit\Newsletter\Entities\Newsletter_lists::class)->create(['title' => 'One Title']);
        $email = factory(App\Droit\Newsletter\Entities\Newsletter_emails::class)->create();

        $response = $this->call('DELETE','build/emails/'.$email->id);

        $this->notSeeInDatabase('newsletter_emails', [
            'email'   => $email->email,
            'list_id' => $liste->id
        ]);
    }
    
    function prepareFileUpload($path)
    {
        return new \Symfony\Component\HttpFoundation\File\UploadedFile($path, null, \File::mimeType($path), null, null, true);
    }
}
