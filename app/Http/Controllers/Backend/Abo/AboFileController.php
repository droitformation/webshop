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
    protected $columns;

    public function __construct(AboInterface $abo, ProductInterface $product, AboFactureWorkerInterface $facture, AboRappelWorkerInterface $rappel)
    {
        $this->abo        = $abo;
        $this->product    = $product;
        $this->facture    = $facture;
        $this->rappel     = $rappel;

        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->columns = config('columns.names');
        $this->columns['exemplaires'] = 'Exemplaires';
        $this->columns['numero'] = 'Numero';
;	}

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

        $this->$worker->generate($product,$abo, $request->input('all'), $date, $request->input('print',null));

        flash('La création des '.$worker.'s est en cours.<br/>Un email vous sera envoyé dès que la génération sera terminée.')->success();

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

        flash('Les '.$worker.'s sont re-attachés, cela peut prendre quelques secondes.<br/>Rafraichissez la page pour mettre à jour le document.')->success();

        return redirect()->back();
    }

    public function export(Request $request){

        $abo     = $this->abo->find($request->input('id'));
        $status  =  !empty($request->input('status')) ? $request->input('status') : ['abonne','tiers','gratuit'];
        $abonnes = $abo->abonnements->whereIn('status', $status );

        $adresses = $this->prepareAdresse($abonnes, $request->input('facturation',null));

        $defaultStyle = (new StyleBuilder())->setFontName('Arial')->setFontSize(11)->build();
        $writer = WriterFactory::create(Type::XLSX); // for XLSX files

        $filename = storage_path('excel/abo_statut_'.$request->input('status','tous').'.xlsx');

        $writer->openToBrowser($filename);
        $writer->addRowWithStyle($this->columns,$defaultStyle); // add multiple rows at a time

        if(!$adresses->isEmpty()){
            $writer->addRowsWithStyle($adresses->toArray(),$defaultStyle); // add multiple rows at a time
        }

        $writer->close();exit;
    }

    public function prepareAdresse($abonnes, $facturation = null)
    {
        return $abonnes->map(function ($abo) use ($facturation) {

            $adresse = $facturation ? $abo->user_facturation : $abo->user_adresse;

            if(isset($adresse)){
                return [
                    'civilite_title'   => trim($adresse->civilite_title),
                    'first_name'       => trim($adresse->first_name),
                    'last_name'        => trim($adresse->last_name),
                    'email'            => $facturation ? '' : trim($adresse->email),
                    'profession_title' => $facturation ? '' : trim($adresse->profession_title),
                    'company'          => trim($adresse->company),
                    'telephone'        => trim($adresse->telephone),
                    'mobile'           => trim($adresse->mobile),
                    'adresse'          => trim($adresse->adresse),
                    'cp_trim'          => trim($adresse->cp_trim),
                    'complement'       => trim($adresse->complement),
                    'npa'              => trim($adresse->npa),
                    'ville'            => trim($adresse->ville),
                    'canton_title'     => trim($adresse->canton_title),
                    'pays_title'       => trim($adresse->pays_title),
                    'exemplaires'      => trim($abo->exemplaires),
                    'numero'           => trim($abo->numero),
                ];
            }

            return '';

        });
    }
}