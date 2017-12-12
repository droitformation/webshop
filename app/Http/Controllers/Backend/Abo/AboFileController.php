<?php namespace App\Http\Controllers\Backend\Abo;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;

use App\Droit\Abo\Worker\AboFactureWorkerInterface;
use App\Droit\Abo\Worker\AboRappelWorkerInterface;

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Common\Type;

class AboFileController extends Controller {
    
    protected $abo;
    protected $facture;
    protected $rappel;
    protected $product;

    public function __construct(AboInterface $abo, ProductInterface $product, AboFactureWorkerInterface $facture, AboRappelWorkerInterface $rappel)
    {
        $this->abo        = $abo;
        $this->product    = $product;
        $this->facture    = $facture;
        $this->rappel     = $rappel;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
	}

    /*
     * Generate all invoices and bind the all
    **/
    public function generate(Request $request)
    {
        $abo     = $this->abo->findAboByProduct($request->input('product_id'));
        $product = $this->product->find($request->input('product_id'));

        $worker  = $request->input('worker');

        $date = $request->input('date',date('Y-m-d'));
        $date = \Carbon\Carbon::parse($date)->toDateTimeString();

        $this->$worker->generate($product,$abo, $request->input('all',false), $date);

        alert()->success('La création des '.$worker.'s est en cours.<br/>Un email vous sera envoyé dès que la génération sera terminée.');

        return redirect()->back();
    }

    /*
   * Bind all invoices
   * */
    public function bind(Request $request)
    {
        $abo     = $this->abo->findAboByProduct($request->input('product_id'));
        $product = $this->product->find($request->input('product_id'));
        
        $worker  = $request->input('worker');
        
        $this->$worker->bind($product, $abo);

        alert()->success('Les '.$worker.'s sont re-attachés<br/>Rafraichissez la page pour mettre à jour le document.');

        return redirect()->back();
    }

    public function export(Request $request){

        $abo     = $this->abo->find($request->input('id'));
        $abonnes = $abo->abonnements->whereIn('status',$request->input('status'));

        $adresses = $this->prepareAdresse($abonnes->pluck('user_adresse'));

        $defaultStyle = (new StyleBuilder())->setFontName('Arial')->setFontSize(11)->build();
        $writer = WriterFactory::create(Type::XLSX); // for XLSX files

        $filename = storage_path('excel/abo_statut_'.$request->input('status').'.xlsx');
        $columns = array_values(config('columns.names'));

        $writer->openToBrowser($filename);
        $writer->addRowWithStyle($columns,$defaultStyle); // add multiple rows at a time

        if(!$adresses->isEmpty()){
            $writer->addRowsWithStyle($adresses->toArray(),$defaultStyle); // add multiple rows at a time
        }

        $writer->close();exit;
    }

    public function prepareAdresse($adresses)
    {
        $columns = collect(array_keys(config('columns.names')));

        return $adresses->map(function ($adresse) use ($columns) {
            return $columns->map(function ($column) use ($adresse) {
                return isset($adresse) ? trim($adresse->$column) : '';
            });
        });
    }
}