<?php namespace App\Droit\Generate\Pdf;

use Carbon\Carbon;
use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\User\Repo\UserInterface;

use App\Droit\Generate\Entities\OrderGenerate;

class PdfGenerator implements PdfGeneratorInterface
{
    protected $now;

    public $stream = false;

    /**
     * Facture shop
     **/
    public $messages = ['remerciements' => 'Avec nos remerciements, nous vous adressons nos salutations les meilleures.'];
    public $centre   = 'U. 01852';
    public $motif    = 'Vente ouvrages';

    public $expediteur = [
        'nom'     => 'Secr&eacute;tariat - Formation',
        'adresse' => 'Avenue du 1er-Mars 26',
        'ville'   => 'CH-2000 Neuch&acirc;tel'
    ];

    public $versement = [
        'nom'     => 'Faculté de droit',
        'adresse' => 'Avenue du 1er-Mars 26',
        'ville'   => '2000 Neuchâtel'
    ];

    public $tva;

    public $signature = 'Le secrétariat de la Faculté de droit';

    public function __construct()
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->now   = Carbon::now()->formatLocalized('%d %B %Y');

        $this->tva = [
            'numero'      => \Registry::get('shop.infos.tva'),
            'taux_reduit' => \Registry::get('shop.infos.taux_reduit'),
            'taux_normal' => \Registry::get('shop.infos.taux_normal')
        ];

        $this->versement = [
            'nom'     => \Registry::get('shop.infos.nom'),
            'adresse' => \Registry::get('shop.infos.adresse'),
        ];
    }

    /*
     * Set messages
     * Type: warning,special,message,remerciements
     */
    public function setMsg($messages)
    {
        foreach($messages as $type => $message)
        {
            $this->messages[$type] = $message;
        }
    }

    /*
     * Set taux tva
     * Type: warning,special,message,remerciements
     */
    public function setTva($tva)
    {
        $this->tva = $tva;
    }

    public function factureOrder($order, $rappel = null)
    {
        $data     = $this->getData('order');
        $generate = new OrderGenerate($order);

        $data['generate'] = $generate;
        $data['rappel']   = $rappel ? $rappel : null;

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
            ]
        ]);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->setHttpContext($context);

        $facture = $pdf->loadView('templates.shop.facture', $data)->setPaper('a4');

        $make = ($this->stream ? 'stream' : 'save');
        $name = $generate->getFilename($rappel);

        return $facture->$make($name);
    }

    public function makeAbo($document, $model, $rappel = null, $rappel_model = null)
    {
        $data     = $this->getData('abo');
        $generate = new \App\Droit\Generate\Entities\Generate($model);

        $data['generate'] = $generate;
        $data['rappel']   = $rappel;

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
            ]
        ]);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->setHttpContext($context);

        $view = $pdf->loadView('templates.abonnement.facture', $data)->setPaper('a4');

        // Do wee need to stream or save the pdf
        $state    = ($this->stream ? 'stream' : 'save');
        $name     = ($rappel ? 'rappel_'.$rappel_model->id : $document);

        // Path for saving document
        $filepath = $generate->getFilename($document, $name);

        return $view->$state($filepath);
    }

    /*
     * Generate documents for inscription
     * Bon, Facture, BV, Attestation
     *
     * Create a Generate object and retrieve data for template type
     * Inject qrcode for Bon => inscription validation via :
     * inscription id and security key
     * Route::get('presence/{id}/{key}', 'CodeController@presence');
     *
     * For abo pass facture and get abo via Generate object
     * */
    public function make($document, $model, $rappel = null)
    {
        $data     = $this->getData($document);
        $generate = new \App\Droit\Generate\Entities\Generate($model);

        $data['generate'] = $generate;

        if($rappel){
            $data['rappel'] = $model->load('rappels')->rappels->count();
        }

        // Qrcode for bon
        if(\Registry::get('inscription.qrcode') && $document == 'bon')
        {
            // url for qrcode if there is occurrences
            $occurrence   = $generate->getOccurrences() ? 'occurrence/' : '';
            $url          = url('presence/'.$occurrence.$model->id.'/'.config('services.qrcode.key'));
            $data['code'] = base64_encode(\QrCode::format('png')->margin(3)->size(115)->encoding('UTF-8')->generate($url));
        }

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
            ]
        ]);
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->setHttpContext($context);

        $view = $pdf->loadView('templates.colloque.'.$document, $data)->setPaper('a4');

        // Do wee need to stream or save the pdf
        $state    = ($this->stream ? 'stream' : 'save');
        $document = ($rappel ? 'rappel' : $document);
        $name     = ($rappel ? 'rappel_'.$rappel->id : $document);

        // Path for saving document
        $filepath = $generate->getFilename($document, $name);

        if( env('APP_ENV') == 'testing' )
        {
            $filepath =  storage_path('test/test.pdf');
        }

        return $view->$state($filepath);

    }

    public function getData($document)
    {
        $data['expediteur']  = $this->expediteur;
        $data['date']        = $this->now;

        if($document == 'facture')
        {
             $data['messages']  = $this->messages;
             $data['signature'] = $this->signature;
             $data['tva']       = $this->tva;
        }

        if($document == 'order')
        {
            $data['messages']  = $this->messages;
            $data['signature'] = $this->signature;
            $data['tva']       = $this->tva;
            $data['versement'] = $this->versement;
            $data['motif'] = [
                'centre' => $this->centre,
                'texte'  => $this->motif,
            ];
            $data['tva'] = [
                'taux_reduit' => 'Taux '.$this->tva['taux_reduit'].'% inclus pour les livres',
                'taux_normal' => 'Taux '.$this->tva['taux_normal'].'% pour les autres produits'
            ];
        }

        if($document == 'abo')
        {
            $data['messages']  = $this->messages;
            $data['versement'] = $this->versement;
            $data['signature'] = $this->signature;
            $data['tva']       = ['taux_reduit' => 'Taux '.$this->tva['taux_reduit'].'% inclus pour les livres'];
            $data['msgTypes']  = ['warning','special','remarque','signature'];
            $data['compte']    = \Registry::get('abo.compte');
        }

        return $data;
    }
}