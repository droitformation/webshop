<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \Symfony\Component\HttpFoundation\File\UploadedFile;

class RappelWorker extends BrowserKitTest {

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();
    }

    public function tearDown()
    {
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }

    public function testPassWorker()
    {
        $worker = App::make('App\Droit\Abo\Worker\AboRappelWorkerInterface');
        $merger = Mockery::mock('Clegginabox\PDFMerger\PDFMerger');
        
        $maker = new \App\Droit\Service\RappelMaker($worker,$merger);
        
        $this->assertEquals(get_class($maker->worker), 'App\Droit\Abo\Worker\AboRappelWorker');
    }

    public function testBind()
    {
        $worker = Mockery::mock('App\Droit\Abo\Worker\AboRappelWorkerInterface');
        $merger = Mockery::mock('Clegginabox\PDFMerger\PDFMerger');

        $maker = new \App\Droit\Service\RappelMaker($worker,$merger);

        \File::shouldReceive('exists')->twice()->andReturn(true);
        \File::shouldReceive('files')->once()->andReturn(['files/test.png']);

        $merger->shouldReceive('addPDF')->once();
        $merger->shouldReceive('merge')->once();

        // Set paths
        $maker->setOutputDirectory('files')->setBoundFilename('name')->bind();

    }

    /**
     * @expectedException \App\Exceptions\InfoMissingException
     */
    public function testBindWithoutPath()
    {
        $worker = Mockery::mock('App\Droit\Abo\Worker\AboRappelWorkerInterface');
        $merger = Mockery::mock('Clegginabox\PDFMerger\PDFMerger');

        $maker = new \App\Droit\Service\RappelMaker($worker,$merger);

        $maker->bind();
    }

    public function testGenerate()
    {
        $factory = new \tests\factories\ObjectFactory();

        $worker = Mockery::mock('App\Droit\Abo\Worker\AboRappelWorkerInterface');
        $merger = Mockery::mock('Clegginabox\PDFMerger\PDFMerger');

        $maker = new \App\Droit\Service\RappelMaker($worker,$merger);

        $abonnement = $factory->makeAbonnement();
        $factory->abonnementFacture($abonnement);

        $facture = $abonnement->factures->first();

        $worker->shouldReceive('generate')->once();

        $maker->setCollection(collect([$facture]))->makeRappels();

    }
}
