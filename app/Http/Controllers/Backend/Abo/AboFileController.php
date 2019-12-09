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

        alert()->success('Les '.$worker.'s sont re-attachés, cela peut prendre quelques secondes.<br/>Rafraichissez la page pour mettre à jour le document.');

        return redirect()->back();
    }

    public function export(Request $request){

        $abo     = $this->abo->find($request->input('id'));
        $status  =  !empty($request->input('status')) ? $request->input('status') : ['abonne','tiers','gratuit'];
        $abonnes = $abo->abonnements->whereIn('status', $status );

        $status = $request->input('status','tous');
        /*
              $adresses = $this->prepareAdresse($abonnes);

           $defaultStyle = (new StyleBuilder())->setFontName('Arial')->setFontSize(11)->build();
              $writer = WriterFactory::create(Type::XLSX); // for XLSX files

              $filename = storage_path('excel/abo_statut_'.$request->input('status','tous').'.xlsx');

              $writer->openToBrowser($filename);
              $writer->addRowWithStyle($this->columns,$defaultStyle); // add multiple rows at a time

              if(!$adresses->isEmpty()){
                  $writer->addRowsWithStyle($adresses->toArray(),$defaultStyle); // add multiple rows at a time
              }

              $writer->close();exit;*/

        $adresses = $this->prepareAdresse($abonnes);
        ////////////
        \Excel::create('abo_statut_'.$status.'.xls', function ($excel) use ($adresses){
            $excel->sheet('status', function ($sheet) use ($adresses){
                $sheet->appendRow($this->columns);
                $sheet->row($sheet->getHighestRow(), function ($row) { $row->setFontWeight('bold')->setFontSize(14);});
                $sheet->rows($adresses);
            });
        })->download('xlsx');

    }

    public function prepareAdresse($abonnes)
    {
        return $abonnes->map(function ($abo) {

            if(isset($abo->user_adresse)){
                return [
                    'civilite_title'   => trim($abo->user_adresse->civilite_title),
                    'first_name'       => trim($abo->user_adresse->first_name),
                    'last_name'        => trim($abo->user_adresse->last_name),
                    'email'            => trim($abo->user_adresse->email),
                    'profession_title' => trim($abo->user_adresse->profession_title),
                    'company'          => trim($abo->user_adresse->company),
                    'telephone'        => trim($abo->user_adresse->telephone),
                    'mobile'           => trim($abo->user_adresse->mobile),
                    'adresse'          => trim($abo->user_adresse->adresse),
                    'cp_trim'          => trim($abo->user_adresse->cp_trim),
                    'complement'       => trim($abo->user_adresse->complement),
                    'npa'              => trim($abo->user_adresse->npa),
                    'ville'            => trim($abo->user_adresse->ville),
                    'canton_title'     => trim($abo->user_adresse->canton_title),
                    'pays_title'       => trim($abo->user_adresse->pays_title),
                    'exemplaires'      => trim($abo->exemplaires),
                    'numero'           => trim($abo->numero),
                ];
            }

            return '';

        });
    }
}