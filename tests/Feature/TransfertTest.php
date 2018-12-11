<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransfertTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetOldModel()
    {
        $transfert = new \App\Droit\Services\Transfert();

        $model = $transfert->getOld('Categorie');

        $this->assertEquals($model->getConnectionName(),'transfert');
        $this->assertTrue($model instanceof \App\Droit\Categorie\Entities\Categorie);

        $model = $transfert->makeNew('Categorie');

        $this->assertEquals($model->getConnectionName(),'mysql');
        $this->assertTrue($model instanceof \App\Droit\Categorie\Entities\Categorie);
    }

    public function testExistAuthorInNewDatabase()
    {
        $author  = factory(\App\Droit\Author\Entities\Author::class)->create();

        $transfert = new \App\Droit\Services\Transfert();

        $exist = $transfert->existAuthor($author->first_name,$author->last_name, 'testing');

        $this->assertEquals($exist->id,$author->id);
    }

    public function testValidDate()
    {
        $transfert = new \App\Droit\Services\Transfert();

        $this->assertTrue($transfert->valid('2018-11-12'));
        $this->assertFalse($transfert->valid('0000-00-00 00:00:00'));
        $this->assertFalse($transfert->valid(null));
    }

    public function testSubscriberExist()
    {
        $transfert = new \App\Droit\Services\Transfert();

        $subscriber = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->make([
            'email' => 'info@publications-droit.ch',
            'activation_token' => 'adsfgtgtfwswd',
            'activated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $model = $transfert->exist($subscriber,$connection = 'testing');

        $this->assertEquals($model->email,'info@publications-droit.ch');
    }

    public function testMakeNewSite()
    {
        $data = [
            'nom'    => 'Droit du travail',
            'url'    => 'http://droitdutravail.ch',
            'logo'   => 'droitdutravail.svg',
            'slug'   => 'droitdutravail',
            'prefix' => 'droitdutravail'
        ];

        $transfert = new \App\Droit\Services\Transfert();

        $transfert->makeSite($data,$connection = 'testing');

        $this->assertDatabaseHas('sites',$data);
    }

    public function testArretsRelationsAreFromOld()
    {
        $transfert = new \App\Droit\Services\Transfert();
        $model = $transfert->getOld('Arret');

        $one = $model->take(1)->get()->first();
        $two = $transfert->arrets()[0];

        $this->assertEquals($one->id,$two['id']);

        $this->assertEquals($one->analyses->first()->getConnection()->getDatabaseName(),'ddt');
        $this->assertEquals($one->categories->first()->getConnection()->getDatabaseName(),'ddt');
    }

    public function testAnalyseRelationsAreFromOld()
    {
        $transfert = new \App\Droit\Services\Transfert();
        $model = $transfert->getOld('Analyse');

        $one = $model->take(1)->get()->first();

        $this->assertEquals($one->arrets->first()->getConnection()->getDatabaseName(),'ddt');
        $this->assertEquals($one->categories->first()->getConnection()->getDatabaseName(),'ddt');
        $this->assertEquals($one->authors->first()->getConnection()->getDatabaseName(),'ddt');
    }

    public function testCategorieRelationsAreFromOld()
    {
        $transfert = new \App\Droit\Services\Transfert();
        $model = $transfert->getOld('Groupe','Arret');

        $one = $model->take(1)->get()->first();

        $this->assertEquals($one->arrets->first()->getConnection()->getDatabaseName(),'ddt');
    }

    public function testNewsletterSubscriberOld()
    {
        $transfert = new \App\Droit\Services\Transfert();
        $model = $transfert->getOld('Newsletter','Newsletter');

        $one = $model->take(1)->get()->first();

        $this->assertEquals($one->subscriptions->first()->getConnection()->getDatabaseName(),'ddt');

        $model2 = $transfert->getOld('Newsletter_users','Newsletter');

        $two = $model2->take(1)->get()->first();

        $this->assertEquals($two->subscriptions->first()->getConnection()->getDatabaseName(),'ddt');
    }

    public function testNewsletterSubscriberNew()
    {
        $one = \App\Droit\Newsletter\Entities\Newsletter::take(1)->get()->first();

        $subscriber = factory(\App\Droit\Newsletter\Entities\Newsletter_users::class)->create([
            'email' => 'info@publications-droit.ch',
            'activation_token' => 'adsfgtgtfwswd',
            'activated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        $subscriber->subscriptions()->attach($one->id);
        $one = $one->load('subscriptions');

        $this->assertEquals($one->subscriptions->first()->getConnection()->getDatabaseName(),'staging');
    }
}
