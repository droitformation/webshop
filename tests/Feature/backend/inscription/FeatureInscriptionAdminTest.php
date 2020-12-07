<?php

namespace Tests\Feature\backend\inscription;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;
use Tests\TestFlashMessages;

class FeatureInscriptionAdminTest extends TestCase
{
    use RefreshDatabase,ResetTbl,TestFlashMessages;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    /********/

    public function testSearchInscription()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        // All inscriptions
        $reponse = $this->get('admin/inscription/colloque/'.$colloque->id);
        $reponse->assertViewHas('inscriptions');

        $content = $reponse->getOriginalContent();
        $content = $content->getData();

        $inscriptions = $content['inscriptions'];

        $this->assertEquals($colloque->inscriptions->count(), $inscriptions->count());

        // with search results
        $this->call('POST', 'admin/inscription/colloque/'.$colloque->id, ['inscription_no' => $inscription->inscription_no]);

        $reponse = $this->get('admin/inscription/colloque/'.$colloque->id);
        $content = $reponse->getOriginalContent();
        $content = $content->getData();

        $inscriptions = $content['inscriptions'];
        $result  = $inscriptions->first();

        $this->assertEquals($result->inscription_no, $inscription->inscription_no);
    }

    public function testSearchUserInfoInscription()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser(['company' => 'Acme']);

        $prices    = $colloque->prices->pluck('id')->all();

        $inscription = factory(\App\Droit\Inscription\Entities\Inscription::class)->create([
            'user_id'     => $person->id,
            'price_id'    => $prices[0],
            'colloque_id' => $colloque->id
        ]);

        // with search results
        $this->call('POST', 'admin/inscription/colloque/'.$colloque->id, ['search' => 'Acme']);

        $reponse = $this->get('admin/inscription/colloque/'.$colloque->id);
        $content = $reponse->getOriginalContent();
        $content = $content->getData();

        $inscriptions = $content['inscriptions'];
        $result  = $inscriptions->first();

        $this->assertEquals($result->user->adresses->first()->company, 'Acme');

        // with search results
        $this->call('POST', 'admin/inscription/colloque/'.$colloque->id, ['search' => 'Designpond']);

        $reponse = $this->get('admin/inscription/colloque/'.$colloque->id);
        $content = $reponse->getOriginalContent();
        $content = $content->getData();

        $inscriptions = $content['inscriptions'];
        $result  = $inscriptions->first();

        $this->assertNotEquals($result->user->adresses->first()->company, 'Designpond');
    }

    public function testCreateInscription()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $reponse = $this->get('admin/inscription/create');
        $reponse->assertViewHas('colloques');
    }

    public function testCreateInscriptionColloque()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $reponse = $this->get('admin/inscription/create/'.$colloque->id);
        $reponse->assertViewHas('colloques');
    }

    public function testDesinscription()
    {
        \DB::table('colloque_inscriptions')->truncate();
        \DB::table('colloque_inscriptions_participants')->truncate();

        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = factory(\App\Droit\User\Entities\User::class)->create();

        $inscription = factory(\App\Droit\Inscription\Entities\Inscription::class)->create(['user_id' => $person->id, 'group_id' => null, 'colloque_id' => $colloque->id]);

        $this->call('DELETE', 'admin/inscription/'.$inscription->id);

        $this->assertDatabaseMissing('colloque_inscriptions', [
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
            'deleted_at'  => null
        ]);

        $this->call('POST', 'admin/desinscription/restore/'.$inscription->id);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
            'deleted_at'  => null
        ]);
    }

    public function testDesinscriptionGroup()
    {
        \DB::table('colloque_inscriptions')->truncate();
        \DB::table('colloque_inscriptions_participants')->truncate();

        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1, 1);

        $inscriptions = $colloque->inscriptions->filter(function ($inscription, $key) {
            return $inscription->group_id;
        });

        $participants = $inscriptions->pluck('participant');

        $groupe = $inscriptions->first();

        $response = $this->call('DELETE', 'admin/group/'.$groupe->groupe->id);

        $this->assertDatabaseMissing('colloque_inscriptions', [
            'colloque_id' => $colloque->id,
            'group_id'    => $groupe->groupe->id,
            'deleted_at'  => null
        ]);

        foreach ($participants as $participant){
            $this->assertDatabaseMissing('colloque_inscriptions_participants', [
                'id' => $participant->id,
                'deleted_at'  => null
            ]);
        }

    }

    public function testMakeSimpleInscription()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $prices   = $colloque->prices->pluck('id')->all();
        $options  = $colloque->options->pluck('id')->all();

        // See page with data
        $data = ['colloque_id' => $colloque->id, 'user_id' => $person->id,'type' => 'simple'];
        $response = $this->call('POST', 'admin/inscription/make', $data);

        //$response->assertSeeText('Choix du rabais');

        $data = [
            'type'        => 'simple' ,
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
            'price_id'    => "price_id:".$prices[0],
            'colloques' => [
                $colloque->id => [
                    'options'  => [$options[0]]
                ]
            ]
        ];

        $response = $this->call('POST', 'admin/inscription', $data);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
            'price_id'    => $prices[0],
        ]);
    }

    public function testMakeSimpleInscriptionColloques()
    {
        // create colloque
        $make     = new \tests\factories\objectfactory();
        $person   = $make->makeUser();

        $rabais = factory(\App\Droit\Inscription\Entities\Rabais::class)->create(['value' => 10]);

        $colloque1  = $make->colloque();
        $colloque2  = $make->colloque();

        $price1     = factory(\App\Droit\Price\Entities\Price::class)->create(['colloque_id' => $colloque1->id, 'price' => 0, 'description' => 'Price free']);
        $price2     = factory(\App\Droit\Price\Entities\Price::class)->create(['colloque_id' => $colloque2->id, 'price' => 0, 'description' => 'Price free']);
        $price_link = factory( \App\Droit\PriceLink\Entities\PriceLink::class)->create();
        $price_link->colloques()->attach([$colloque1->id,$colloque2->id]);

        $price_rabais = $price_link->price_cents - $rabais->value;

        $options1 = $colloque1->options->pluck('id')->all();
        $options2 = $colloque2->options->pluck('id')->all();

        $option_groupe1 = $make->addGroupOption($colloque1);
        $option_groupe2 = $make->addGroupOption($colloque2);

        // See page with data
        $data = ['colloque_id' => $colloque1->id, 'user_id' => $person->id,'type' => 'simple'];
        $this->call('POST', 'admin/inscription/make', $data);

        $data = [
            'rabais_id'      => $rabais->id,
            'user_id'        => $person->id,
            'colloque_id'    => $colloque1->id,
            'type'           => 'simple',
            'colloques' => [
                $colloque1->id => [
                    'options' => [$options1[0]],
                    'groupes' => [$option_groupe1->id => $option_groupe1->groupe->first()->id]
                ],
                $colloque2->id => [
                    'options' => [$options2[0]]
                ]
            ],
            'price_id' => 'price_link_id:'.$price_link->id,
        ];

        $response = $this->call('POST', 'admin/inscription', $data);

        echo '<pre>';
        print_r($response->getContent());
        echo '</pre>';
        exit;

        $this->assertDatabaseHas('colloque_inscriptions', [
            'colloque_id'     => $colloque1->id,
            'user_id'         => $person->id,
            'rabais_id'       => $rabais->id,
            'price_link_id'   => $price_link->id,
            'price_linked_id' => $price_link->id,
        ]);
        
        $this->assertDatabaseHas('colloque_inscriptions', [
            'colloque_id'     => $colloque2->id,
            'user_id'         => $person->id,
            'price_id'        => $price2->id,
            'price_linked_id' => $price_link->id,
        ]);

        $model  = new \App\Droit\Inscription\Entities\Inscription();
        $inscriptions = $model->all();

        $first  = $inscriptions->shift();
        $second = $inscriptions->shift();

        $this->assertDatabaseHas('colloque_option_users', [
            'inscription_id' => $first->id,
            'option_id'      => $options1[0],
        ]);

        $this->assertDatabaseHas('colloque_option_users', [
            'inscription_id' => $second->id,
            'option_id'      => $options2[0],
        ]);

        $this->assertEquals($price_rabais,$first->price_cents);
        $this->assertEquals(0,$second->price_cents);
    }

    public function testMakeMultipleInscription()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();
        $prices   = $colloque->prices->pluck('id')->all();
        $options  = $colloque->options->pluck('id')->all();

        $option_groupe = $make->addGroupOption($colloque);

        // See page with data
        $data = ['colloque_id' => $colloque->id, 'user_id' => $person->id, 'type' => 'multiple'];
        $this->call('POST', 'admin/inscription/make', $data);

        $data = [
            'colloque_id' => $colloque->id ,
            'user_id'     => $person->id,
            'type'        => 'multiple',
            'participant' => [
                'Cindy Leschaud',
                'Coralie Ahmetaj'
            ],
            'email' => [
                'cindy.leschaud@gmail.com',
                'coralie.ahmetaj@hotmail.com'
            ],
            'price_id'     => [
                "price_id:".$prices[0],
                "price_id:".$prices[0]
            ],
            'addons' => [
                $colloque->id => [
                    'options' => [
                        0 => [$options[0]],
                        1 => [$options[0]]
                    ],
                    'groupes' => [
                        0 => [$option_groupe->id => $option_groupe->groupe[0]->id],
                        1 => [$option_groupe->id => $option_groupe->groupe[1]->id]
                    ]
                ]
            ],
            'colloques' => [
                [$colloque->id],
                [$colloque->id]
            ]
        ];

        $reponse = $this->call('POST', 'admin/inscription', $data);

        $model  = new \App\Droit\Inscription\Entities\Inscription();
        $inscriptions = $model->all();

        $first  = $inscriptions->shift();
        $second = $inscriptions->shift();

        $this->assertDatabaseHas('colloque_inscriptions', [
            'id'          => $first->id,
            'colloque_id' => $colloque->id,
            'group_id'    => $first->group_id,
            'price_id'    => $prices[0],
        ]);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'id'          => $second->id,
            'colloque_id' => $colloque->id,
            'group_id'    => $second->group_id,
            'price_id'    => $prices[0],
        ]);

        $this->assertDatabaseHas('colloque_option_users', [
            'inscription_id' => $first->id,
            'option_id'      => $options[0],
        ]);

        $this->assertDatabaseHas('colloque_option_users', [
            'inscription_id' => $second->id,
            'option_id'      => $options[0],
        ]);

        $this->assertDatabaseHas('colloque_option_users', [
            'inscription_id' => $first->id,
            'option_id'      => $option_groupe->id,
            'groupe_id'      => $option_groupe->groupe[0]->id,
        ]);

        $this->assertDatabaseHas('colloque_option_users', [
            'inscription_id' => $second->id,
            'option_id'      => $option_groupe->id,
            'groupe_id'      => $option_groupe->groupe[1]->id,
        ]);
    }

    public function testMakeMultipleInscriptionsPriceLink()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $person   = $make->makeUser();

        $colloque1  = $make->colloque();
        $colloque2  = $make->colloque();

        $price1     = factory(\App\Droit\Price\Entities\Price::class)->create(['colloque_id' => $colloque1->id, 'price' => 0, 'description' => 'Price free']);
        $price2     = factory(\App\Droit\Price\Entities\Price::class)->create(['colloque_id' => $colloque2->id, 'price' => 0, 'description' => 'Price free']);
        $price_link = factory( \App\Droit\PriceLink\Entities\PriceLink::class)->create();
        $price_link->colloques()->attach([$colloque1->id,$colloque2->id]);

        $options1 = $colloque1->options->pluck('id')->all();
        $options2 = $colloque2->options->pluck('id')->all();

        $option_groupe1 = $make->addGroupOption($colloque1);
        $option_groupe2 = $make->addGroupOption($colloque2);

        // See page with data
        $data = ['colloque_id' => $colloque1->id, 'user_id' => $person->id, 'type' => 'multiple'];
        $this->call('POST', 'admin/inscription/make', $data);

        $data = [
            'colloque_id' => $colloque1->id ,
            'user_id'     => $person->id,
            'type'        => 'multiple',
            'participant' => [
                'Cindy Leschaud',
                'Coralie Ahmetaj'
            ],
            'email' => [
                'cindy.leschaud@gmail.com',
                'coralie.ahmetaj@hotmail.com'
            ],
            'price_id'     => [
                "price_link_id:".$price_link->id,
                "price_link_id:".$price_link->id
            ],
            'addons' => [
                $colloque1->id => [
                    'options' => [
                        0 => [$options1[0]],
                        1 => [$options1[0]]
                    ],
                    'groupes' => [
                        0 => [$option_groupe1->id => $option_groupe1->groupe[0]->id],
                        1 => [$option_groupe1->id => $option_groupe1->groupe[1]->id]
                    ]
                ],
                $colloque2->id => [
                    'options' => [
                        0 => [$options2[0]],
                        1 => [$options2[0]]
                    ],
                    'groupes' => [
                        0 => [$option_groupe2->id => $option_groupe2->groupe[0]->id],
                        1 => [$option_groupe2->id => $option_groupe2->groupe[1]->id]
                    ]
                ]
            ],
            'colloques' => [
                [$colloque1->id,$colloque2->id],
                [$colloque1->id,$colloque2->id],
            ]
        ];

        $reponse = $this->call('POST', 'admin/inscription', $data);

        $model  = new \App\Droit\Inscription\Entities\Inscription();
        $inscriptions = $model->all();
        $first  = $inscriptions->shift();
        $second = $inscriptions->shift();

        $third  = $inscriptions->shift();
        $fourth = $inscriptions->shift();

        $this->assertDatabaseHas('colloque_inscriptions', [
            'id'            => $first->id,
            'colloque_id'   => $colloque1->id,
            'group_id'      => $first->group_id,
            'price_link_id' => $price_link->id,
            'price_linked_id' => $price_link->id,
        ]);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'id'              => $second->id,
            'colloque_id'     => $colloque1->id,
            'group_id'        => $second->group_id,
            'price_link_id'   => $price_link->id,
            'price_linked_id' => $price_link->id,
        ]);

        $this->assertDatabaseHas('colloque_option_users',['inscription_id' => $first->id, 'option_id' => $options1[0]]);
        $this->assertDatabaseHas('colloque_option_users',['inscription_id' => $second->id,'option_id' => $options1[0]]);

        $this->assertDatabaseHas('colloque_option_users',['inscription_id' => $first->id, 'option_id' => $option_groupe1->id, 'groupe_id' => $option_groupe1->groupe[0]->id]);
        $this->assertDatabaseHas('colloque_option_users',['inscription_id' => $second->id,'option_id' => $option_groupe1->id, 'groupe_id' => $option_groupe1->groupe[1]->id]);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'id'          => $third->id,
            'colloque_id' => $colloque2->id,
            'group_id'    => $third->group_id,
            'price_id'    => $price2->id,
            'price_linked_id' => $price_link->id,
        ]);
        $this->assertDatabaseHas('colloque_inscriptions', [
            'id'          => $fourth->id,
            'colloque_id' => $colloque2->id,
            'group_id'    => $fourth->group_id,
            'price_id'    => $price2->id,
            'price_linked_id' => $price_link->id,
        ]);

        $this->assertDatabaseHas('colloque_option_users',['inscription_id' => $third->id, 'option_id' => $options2[0]]);
        $this->assertDatabaseHas('colloque_option_users',['inscription_id' => $fourth->id,'option_id' => $options2[0]]);

        $this->assertDatabaseHas('colloque_option_users',['inscription_id' => $third->id, 'option_id' => $option_groupe2->id, 'groupe_id' => $option_groupe2->groupe[0]->id]);
        $this->assertDatabaseHas('colloque_option_users',['inscription_id' => $fourth->id,'option_id' => $option_groupe2->id, 'groupe_id' => $option_groupe2->groupe[1]->id]);

    }

    public function testEditColumnsViaAjax()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $inscription = factory(\App\Droit\Inscription\Entities\Inscription::class)->create(['user_id' => $person->id, 'group_id' => null, 'colloque_id' => $colloque->id]);

        $data = [
            'model'    => 'inscription' ,
            'pk'       => $inscription->id,
            'name'     => 'payed_at',
            'value'    => \Carbon\Carbon::now()->toDateString()
        ];

        $result = ['OK' => 200, 'etat' => 'Payé','color' => 'success'];

        $this->json('POST', 'admin/inscription/edit', $data)->assertJsonFragment($result);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'id'          => $inscription->id,
            'colloque_id' => $colloque->id,
            'payed_at'    => \Carbon\Carbon::now()->toDateString(),
        ]);
    }

    public function testEditGroupeColumnsViaAjax()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1, 1);

        $inscriptions = $colloque->inscriptions->filter(function ($inscription, $key) {
            return $inscription->group_id;
        });

        $groupe = $inscriptions->first();

        $data = [
            'model'    => 'group' ,
            'pk'       => $groupe->groupe->id,
            'name'     => 'payed_at',
            'value'    => \Carbon\Carbon::now()->toDateString()
        ];

        $result = ['OK' => 200, 'etat' => 'Payé','color' => 'success'];

        $this->json('POST', 'admin/inscription/edit', $data)->assertJsonFragment($result);

        foreach($inscriptions as $inscription)
        {
            $this->assertDatabaseHas('colloque_inscriptions', [
                'id'          => $inscription->id,
                'colloque_id' => $colloque->id,
                'payed_at'    => \Carbon\Carbon::now()->toDateString(),
            ]);
        }
    }

    public function testUpdateInscriptionAndDocs()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $prices   = $colloque->prices->pluck('id')->all();
        $options  = $colloque->options->pluck('id')->all();

        $date = \Carbon\Carbon::now()->toDateString();

        $inscription = factory(\App\Droit\Inscription\Entities\Inscription::class)->create([
            'user_id'     => $person->id,
            'group_id'    => null,
            'colloque_id' => $colloque->id,
            'payed_at'    => null,
        ]);

        $data = [
            'id'          => $inscription->id,
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
            'payed_at'    => $date,
            'price_id'    => 'price_id:'.$prices[0],
            'options'     => [
                $options[0]
            ]
        ];

        $this->call('PUT', 'admin/inscription/'.$inscription->id, $data);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'id'          => $inscription->id,
            'colloque_id' => $colloque->id,
            'payed_at'    => $date,
        ]);
    }

    /*
        won't work in travis
        public function testMakeBackupDocs()
        {
            // Create colloque
            $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');

            $make     = new \tests\factories\ObjectFactory();
            $colloque = $make->makeInscriptions(1);

            $inscription = $colloque->inscriptions->first();

            $this->assertNull($inscription->doc_bon);
            $this->assertNull($inscription->doc_bv);
            $this->assertNull($inscription->doc_facture);

            $this->makeDoc($inscription,'bon');
            $this->makeDoc($inscription,'facture');
            $this->makeDoc($inscription,'bv');

            sleep(3);

            $this->assertNotNull($inscription->doc_bon);
            $this->assertNotNull($inscription->doc_bv);
            $this->assertNotNull($inscription->doc_facture);

            $price = factory(\App\Droit\Price\Entities\Price::class)->create(['price' => 0, 'colloque_id' => $colloque->id]);

            $inscription->price_id = $price->id;
            $inscription->save();
            $inscription->fresh();
            $inscription->load('price');

            $worker->makeDocuments($inscription,true);

            sleep(3);

            $inscription->fresh();

            $this->assertNotNull($inscription->doc_bon);
            $this->assertNull($inscription->doc_bv);
            $this->assertNull($inscription->doc_facture);
        }*/

    public function makeDoc($model,$document)
    {
        $data['messages']  = ['remerciements' => 'Avec nos remerciements, nous vous adressons nos salutations les meilleures.'];
        $data['signature'] = 'Le secrétariat de la Faculté de droit';
        $data['tva']       = [
            'numero'      => \Registry::get('shop.infos.tva'),
            'taux_reduit' => \Registry::get('shop.infos.taux_reduit'),
            'taux_normal' => \Registry::get('shop.infos.taux_normal')
        ];

        $generate = new \App\Droit\Generate\Entities\Generate($model);
        $data['generate']  = $generate;

        $context = stream_context_create([
            'ssl' => ['verify_peer' => FALSE, 'verify_peer_name' => FALSE, 'allow_self_signed'=> TRUE]
        ]);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->setHttpContext($context);

        $view = $pdf->loadView('templates.colloque.'.$document, $data)->setPaper('a4');

        $filepath = $generate->getFilename($document, $document);

        $view->save($filepath);
    }

    /**
     * Send Inscription from admin
     * @return void
     */
    public function testSendInscription()
    {
        \Mail::fake();

        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $response = $this->call('POST', 'admin/inscription/send', ['id' => $inscription->id, 'model' => 'inscription', 'email' => 'cindy.leschaud@gmail.com']);

        $response->assertSessionHas('flash_notification.0.message', 'Email envoyé');
    }

    /**
     * Send Group inscription from admin
     * @return void
     */
    public function testSendGroupInscription()
    {
        \Mail::fake();

        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1, 1);

        $inscriptions = $colloque->inscriptions;

        $group = $inscriptions->filter(function ($inscription, $key) {
            return $inscription->group_id;
        })->first();

        $response = $this->call('POST', 'admin/inscription/send', ['id' => $group->group_id, 'model' => 'group', 'email' => 'info@leschaud.ch']);

        $this->assertCount(1, $this->flashMessagesForMessage('Email envoyé'));
    }

    public function testSendFails()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $response = $this->call('POST', 'admin/inscription/send', ['id' => 0, 'model' => 'inscription', 'email' => 'cindy.leschaud@gmail.com']);

        $this->assertCount(1, $this->flashMessagesForMessage('Aucune inscription ou groupe trouvé!'));
    }

    public function testUpdateInscriptionAfterSendingKeepConferencesAndOptions()
    {
        $worker   = \App::make('App\Droit\Inscription\Worker\InscriptionWorkerInterface');
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(1);

        $inscription = $colloque->inscriptions->first();

        $occurence = factory(\App\Droit\Occurrence\Entities\Occurrence::class)->create([
            'colloque_id'  => $colloque->id,
            'title'        => 'Titre de la conférence'
        ]);

        $inscription->occurrences()->attach($occurence->id);
        $inscription->fresh();
        $inscription->load('occurrences');

        $this->assertEquals(1,$inscription->occurrences->count());

        $results = $worker->updateInscription($inscription);

        $updated = $results->first();
        $updated->load('occurrences');

        $this->assertEquals(1,$updated->occurrences->count());
    }

    public function testListRappelsNormalColloque()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(3);

        $first  = $colloque->inscriptions->shift();
        $second = $colloque->inscriptions->shift();
        $third  = $colloque->inscriptions->shift();

        // Third is payed not in rappels
        $third->payed_at = \Carbon\Carbon::today()->subDays(5);
        $third->save();

        $response = $this->get('admin/inscription/rappels/'.$colloque->id);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $inscriptions = $content['inscriptions'];

        $this->assertEquals(2, $inscriptions->count());

        // Pay all inscriptions
        $first->payed_at = \Carbon\Carbon::today()->subDays(5);
        $first->save();

        $second->payed_at = \Carbon\Carbon::today()->subDays(5);
        $second->save();

        $response = $this->get('admin/inscription/rappels/'.$colloque->id);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $inscriptions = $content['inscriptions'];

        $this->assertEquals(0, $inscriptions->count());

    }

    public function testListRappelsOccurrencesColloque()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(3);

        $first  = $colloque->inscriptions->shift();
        $second = $colloque->inscriptions->shift();
        $third  = $colloque->inscriptions->shift();

        $occurence1 = factory(\App\Droit\Occurrence\Entities\Occurrence::class)->create([
            'colloque_id'  => $colloque->id,
            'title'        => 'Titre de la conférence today',
            'starting_at'  => \Carbon\Carbon::today()->addDays(5)
        ]);

        $occurence2 = factory(\App\Droit\Occurrence\Entities\Occurrence::class)->create([
            'colloque_id'  => $colloque->id,
            'title'        => 'Titre de la conférence today',
            'starting_at'  => \Carbon\Carbon::today()->subDays(5)
        ]);

        $colloque->occurrences()->saveMany([$occurence1,$occurence2]);

        $first->occurrences()->attach($occurence1->id);
        $first->fresh();
        $first->load('occurrences');

        $this->assertEquals(1,$first->occurrences->count());

        $second->occurrences()->attach($occurence2->id);
        $third->occurrences()->attach([$occurence1->id,$occurence2->id]);

        // First occurrence 1 not passed, not in rappels
        // Second occurrence 2 passed, in rappels
        // Third occurrence 1 and 2 not passed/passed, in rappels

        $response = $this->get('admin/inscription/rappels/'.$colloque->id);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $inscriptions = $content['inscriptions'];

        $this->assertEquals(2, $inscriptions->count());

        $inrappels = [$second->id,$third->id];

        $inscriptions->map(function ($item, $key) use ($inrappels) {
            $this->assertTrue(in_array($item->id, $inrappels));
        });
    }

    public function testListRappelsPayedOccurrencesColloque()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->makeInscriptions(3);

        $first  = $colloque->inscriptions->shift();
        $second = $colloque->inscriptions->shift();
        $third  = $colloque->inscriptions->shift();

        $occurence1 = factory(\App\Droit\Occurrence\Entities\Occurrence::class)->create([
            'colloque_id'  => $colloque->id,
            'title'        => 'Titre de la conférence today',
            'starting_at'  => \Carbon\Carbon::today()->addDays(5)
        ]);

        $occurence2 = factory(\App\Droit\Occurrence\Entities\Occurrence::class)->create([
            'colloque_id'  => $colloque->id,
            'title'        => 'Titre de la conférence today',
            'starting_at'  => \Carbon\Carbon::today()->subDays(5)
        ]);

        $colloque->occurrences()->saveMany([$occurence1,$occurence2]);

        $first->occurrences()->attach($occurence1->id);
        $second->occurrences()->attach($occurence2->id);
        $third->occurrences()->attach([$occurence1->id,$occurence2->id]);

        // Pay all inscriptions
        $first->payed_at = \Carbon\Carbon::today()->subDays(5);
        $first->save();

        $second->payed_at = \Carbon\Carbon::today()->subDays(5);
        $second->save();

        $response = $this->get('admin/inscription/rappels/'.$colloque->id);

        $content = $response->getOriginalContent();
        $content = $content->getData();

        $inscriptions = $content['inscriptions'];

        $this->assertEquals(1, $inscriptions->count());
    }

    public function testGroupAdminRegisterWithReferences()
    {
        $make   = new \tests\factories\ObjectFactory();
        $groupe = $make->makeGroupInscription();

        session()->put('reference_no', 'Ref_2019_designpond');
        session()->put('transaction_no', '2109_10_19824');

        $reference = \App\Droit\Transaction\Reference::make($groupe);

        $this->assertDatabaseHas('colloque_inscriptions_groupes', [
            'id' => $groupe->id,
            'reference_id' => $reference->id
        ]);

        $this->assertDatabaseHas('transaction_references', [
            'reference_no' => 'Ref_2019_designpond',
            'transaction_no' => '2109_10_19824'
        ]);
    }

    public function testMakeSimpleWithReferenceInscription()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $prices   = $colloque->prices->pluck('id')->all();
        $options  = $colloque->options->pluck('id')->all();

        // See page with data
        $data = [
            'colloque_id' => $colloque->id,
            'user_id' => $person->id,
            'type' => 'simple',
        ];

        $this->call('POST', 'admin/inscription/make', $data);

        $data = [
            'type'           => 'simple',
            'reference_no'   => 'Ref_2019_desipond',
            'transaction_no' => '29_10_19824',
            'colloque_id'    => $colloque->id,
            'user_id'        => $person->id,
            'price_id'       => 'price_id:'.$prices[0],
            'colloques' => [
                $colloque->id => [
                    'options'  => [
                        $options[0]
                    ]
                ]
            ]
        ];

        $this->call('POST', 'admin/inscription', $data);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
            'price_id'    => $prices[0],
        ]);

        $this->assertDatabaseHas('transaction_references', [
            'reference_no'   => 'Ref_2019_desipond',
            'transaction_no' => '29_10_19824'
        ]);
    }

    public function testMakeMultipleWithReferenceInscription()
    {
        // Create colloque
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();
        $prices   = $colloque->prices->pluck('id')->all();
        $options  = $colloque->options->pluck('id')->all();

        // See pag with data
        $data = ['colloque_id' => $colloque->id, 'user_id' => $person->id, 'type' => 'multiple'];
        $this->call('POST', 'admin/inscription/make', $data);

        $data = [
            'colloque_id' => $colloque->id ,
            'user_id'     => $person->id,
            'type'        => 'multiple',
            'reference_no'   => 'Ref_2019_depond',
            'transaction_no' => '29_10_1924',
            'participant' => [
                'Cindy Leschaud',
                'Coralie Ahmetaj'
            ],
            'email' => [
                'cindy.leschaud@gmail.com',
                'coralie.ahmetaj@hotmail.com'
            ],
            'price_id'     => [
                "price_id:".$prices[0],
                "price_id:".$prices[0]
            ],
            'addons' => [
                $colloque->id => [
                    'options' => [
                        0 => [$options[0]],
                        1 => [$options[0]]
                    ],
                ]
            ],
            'colloques' => [
                [$colloque->id],
                [$colloque->id]
            ]
        ];

        $reponse = $this->call('POST', 'admin/inscription', $data);

        $inscription  = new \App\Droit\Inscription\Entities\Inscription();

        $latest = $inscription->latest();
        $first  = $inscription->first();

        $this->assertDatabaseHas('colloque_inscriptions', [
            'id'          => $first->id,
            'colloque_id' => $colloque->id,
            'group_id'    => $first->group_id,
            'price_id'    => $prices[0],
        ]);

        $this->assertDatabaseHas('transaction_references', [
            'reference_no'   => 'Ref_2019_depond',
            'transaction_no' => '29_10_1924'
        ]);
    }

    public function testUpdateInscriptionAndDocsWithReferences()
    {
        $make     = new \tests\factories\ObjectFactory();
        $colloque = $make->colloque();
        $person   = $make->makeUser();

        $prices   = $colloque->prices->pluck('id')->all();
        $options  = $colloque->options->pluck('id')->all();

        $date = \Carbon\Carbon::now()->toDateString();

        $inscription = factory(\App\Droit\Inscription\Entities\Inscription::class)->create([
            'user_id' => $person->id,
            'group_id' => null,
            'colloque_id' => $colloque->id,
            'payed_at'    => null,
        ]);

        $data = [
            'id'          => $inscription->id,
            'colloque_id' => $colloque->id,
            'user_id'     => $person->id,
            'reference_no'   => 'Rf_2019_depond',
            'transaction_no' => '29_10_124',
            'payed_at'    => $date,
            'price_id'    => $prices[0],
            'options'     => [
                $options[0]
            ]
        ];

        $this->call('PUT', 'admin/inscription/'.$inscription->id, $data);

        $this->assertDatabaseHas('colloque_inscriptions', [
            'id'          => $inscription->id,
            'colloque_id' => $colloque->id,
            'payed_at'    => $date,
        ]);

        $this->assertDatabaseHas('transaction_references', [
            'reference_no'   => 'Rf_2019_depond',
            'transaction_no' => '29_10_124',
        ]);
    }
}
