<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class ListAcceptTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    public function setUp()
    {
        parent::setUp();

        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testStoreListeUploadFails()
    {
        $file   = dirname(__DIR__).'/excel/test.xlsx';
        $upload = $this->prepareFileUpload($file);

        $response = $this->call('POST', 'build/liste', ['title' => 'Un titre' ,'list_id' => 1, 'campagne_id' => 1], []);

        $response->assertSessionHas('alert.style','danger');
        $response->assertSessionHas('alert.message','Aucun fichier séléctionné');
    }

    public function testAddSpecialisations()
    {
        \DB::table('newsletter_lists')->truncate();

        $list = \App::make('App\Droit\Newsletter\Repo\NewsletterListInterface');
        $make  = new \tests\factories\ObjectFactory();

        $specialisations = $make->items('Specialisation', $nbr = 2);
        $specialisations = $specialisations->pluck('id')->all();

        $result = $list->create(['title' => 'One title', 'emails' => ['cindy.leschaud@gmail.com','pruntrut@yahoo.fr'], 'specialisations' => $specialisations]);

        $this->assertDatabaseHas('newsletter_lists', ['title' => 'One title']);
        $this->assertDatabaseHas('newsletter_emails', ['email' => 'cindy.leschaud@gmail.com']);
        $this->assertDatabaseHas('newsletter_emails', ['email' => 'pruntrut@yahoo.fr']);

        $this->assertEquals(2, $result->emails->count());

        $this->assertEquals($specialisations, $result->specialisations->pluck('id')->all());
    }

    public function testUpdateList()
    {
        \DB::table('newsletter_lists')->truncate();

        $liste = factory(\App\Droit\Newsletter\Entities\Newsletter_lists::class)->create([
            'title' => 'One Title'
        ]);

        $make  = new \tests\factories\ObjectFactory();
        $specialisations = $make->items('Specialisation', $nbr = 2);
        $specialisations = $specialisations->pluck('id')->all();

        $response = $this->call('PUT','build/liste/'.$liste->id, ['id' =>  $liste->id,'title' => 'Other title', 'specialisations' => $specialisations]);
        $response = $this->get('build/liste/'.$liste->id);

        $content = $response->getOriginalContent();
        $content = $content->getData();
        $result  = $content['list'];

        $this->assertEquals($specialisations, $result->specialisations->pluck('id')->all());

        $this->assertDatabaseHas('newsletter_lists', [
            'id'    => $liste->id,
            'title' => 'Other title'
        ]);
    }

    public function testDeleteList()
    {
        \DB::table('newsletter_lists')->truncate();

        $liste = factory(\App\Droit\Newsletter\Entities\Newsletter_lists::class)->create(['title' => 'One Title']);

        $response = $this->call('DELETE','build/liste/'.$liste->id);

        $this->assertDatabaseMissing('newsletter_lists', [
            'id' => $liste->id,
            'deleted_at' => null
        ]);
    }

    public function testValidEmails()
    {
        $results = collect([['email' => 'cindy.leschaud@gmail.com'],['email' => 'cindy.leschaud@gmail.com'],['email' => 'prundaf.ch']]);
        $emails = $results->pluck('email')
            ->unique()->reject(function ($value, $key) {
                return !filter_var($value, FILTER_VALIDATE_EMAIL) || empty($value);
            });

        $this->assertSame(1,$emails->count());
    }

    function prepareFileUpload($path)
    {
        return new \Symfony\Component\HttpFoundation\File\UploadedFile($path, null, \File::mimeType($path), null, null, true);
    }

    public function testAddEmailToList()
    {
        $liste = factory(\App\Droit\Newsletter\Entities\Newsletter_lists::class)->create(['title' => 'One Title']);

        $response = $this->call('POST', 'build/emails', ['list_id' => $liste->id, 'email' => 'cindy.leschaud@gmail.com']);

        $this->assertDatabaseHas('newsletter_emails', [
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
        $liste = factory(\App\Droit\Newsletter\Entities\Newsletter_lists::class)->create(['title' => 'One Title']);
        $email = factory(\App\Droit\Newsletter\Entities\Newsletter_emails::class)->create();

        $response = $this->call('DELETE','build/emails/'.$email->id);

        $this->assertDatabaseMissing('newsletter_emails', [
            'email'   => $email->email,
            'list_id' => $liste->id
        ]);
    }

}
