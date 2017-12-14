<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class AboAdminWorkerTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $worker;
    protected $generator;
    protected $facture;

    public function setUp()
    {
        parent::setUp();
        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);

        $this->worker    = \App::make('App\Droit\Abo\Worker\AboWorkerInterface');
        $this->generator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
        $this->facture   = \App::make('App\Droit\Abo\Repo\AboFactureInterface');
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function testBindFactures()
    {
        $abo      = $this->makeAbosGetProduct();
        $product  = $abo->products->first();
        $factures = $this->facture->getAll($product->id);

        // Directory for edition => product_id
        $dir        = 'files/abos/facture/'.$product->id;
        $name       = 'factures_'.$product->reference.'_'.$product->edition_clean;
        $filename   = 'files/abos/bound/'.$abo->id.'/'.$name.'.pdf';

        $factureDir = public_path($dir);
        $boundDir   = public_path('files/abos/bound/'.$abo->id);

        $this->clean($abo,$factureDir,$boundDir);

        $this->assertEquals(3,$factures->count());

        // Make 3 facture pdf
        foreach ($factures as $facture){
            $this->generator->makeAbo('facture', $facture);
        }

        // Get all files in directory
        $files = \File::files($factureDir);

        $this->assertEquals(3, count($files));

        // Merge 3 factures
        $this->worker->merge($files, $name, $abo->id);

        //$this->assertEquals(3, $this->getNumPagesPdf(public_path($filename)));

        \File::deleteDirectory($factureDir);
        \File::deleteDirectory($boundDir);
    }

    public function clean($abo,$factureDir,$boundDir)
    {
        \File::deleteDirectory($factureDir);
        \File::deleteDirectory($boundDir);

        // Clean directories, empty or make
        if (!\File::exists($factureDir)) {
            \File::makeDirectory($factureDir, 0777 , true);
        }

        if (!\File::exists($boundDir)) {
            \File::makeDirectory($boundDir, 0777 , true);
        }
    }

    public function makeAbosGetProduct()
    {
        // Make abo
        $make   = new \tests\factories\ObjectFactory();
        $abo    = $make->makeAbo();

        // Make 3 abonnements
        $abonnement1 = $make->makeUserAbonnement($abo);
        $abonnement2 = $make->makeUserAbonnement($abo);
        $abonnement3 = $make->makeUserAbonnement($abo);

        // Make 3 factures
        $make->abonnementFacture($abonnement1);
        $make->abonnementFacture($abonnement2);
        $make->abonnementFacture($abonnement3);

        return $abo;
    }

    public function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }
        return true;
    }

    public function getNumPagesPdf($PDFPath) {

        $stream     = @fopen($PDFPath, "r");
        $PDFContent = @fread ($stream, filesize($PDFPath));
        if(!$stream || !$PDFContent)
            return false;

        $firstValue = 0;
        $secondValue = 0;

        if(preg_match("/\/N\s+([0-9]+)/", $PDFContent, $matches)) {
            $firstValue = $matches[1];
        }

        if(preg_match_all("/\/Count\s+([0-9]+)/s", $PDFContent, $matches)) {
            $secondValue = max($matches[1]);
        }

        return (($secondValue != 0) ? $secondValue : max($firstValue, $secondValue));
    }
}