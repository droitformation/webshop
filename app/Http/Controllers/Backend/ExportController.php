<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Adresse\Repo\AdresseInterface;


class ExportController extends Controller
{
    protected $inscription;
    protected $colloque;
    protected $adresse;
    protected $badges;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ColloqueInterface $colloque, InscriptionInterface $inscription, AdresseInterface $adresse )
    {
        $this->inscription = $inscription;
        $this->colloque    = $colloque;
        $this->adresse     = $adresse;

        $this->label = new \App\Droit\Helper\Label();

        $this->badges = config('badge');
    }

    /**
     *
     * @return Response
     */
    public function view(Request $request)
    {
        $request->session()->forget(['terms','download','count']);

        return view('backend.export.user');
    }

    /**
     * Search user global
     *
     * @return Response
     */
    public function search(Request $request)
    {
        if ($request->session()->has('terms'))
        {
            $terms = $request->session()->get('terms');
            $each  = (isset($terms['each']) ? true : false);
        }
        else
        {
            $terms = $request->all();
            $each  = $request->input('each', false);
            $request->session()->put('terms', $request->all());
        }

        $adresses = $this->adresse->searchMultiple($terms, $each, 20);

        $terms = $this->label->nameTerms($terms);
        $count = $adresses->total();

        return view('backend.export.results')->with(['adresses' => $adresses, 'terms' => $terms, 'download' => '', 'count' => $count]);
    }

    /**
     * Export methods
     *
     * @return Response
     */
    public function inscription(Request $request)
    {
        $colloque     = $this->colloque->find($request->input('id'));
        $inscriptions = $this->inscription->getByColloqueExport($colloque->id, $request->input('occurrence', []));

        $exporter = new \App\Droit\Generate\Export\ExportInscription();
        $exporter->setColumns($request->input('columns', config('columns.names')))->setSort($request->input('sort', false));
        
        ini_set('memory_limit', '-1');
        
        $exporter->export($inscriptions, $colloque);
    }

    public function generate(Request $request)
    {
        $terms    = $request->session()->get('terms');
        $each     = (isset($terms['each']) ? true : false);

        // Get adresses with terms
        $adresses = $this->adresse->searchMultiple($terms, $each);

        // Export adresses
        $exporter = new \App\Droit\Generate\Export\ExportAdresse();

        $filename = $exporter->export($adresses);

        return response()->download($filename, 'export_'.date("d-m-Y H:i").'.csv', ['Content-Type' => 'text/csv']);
    }

    public function badges(Request $request)
    {
        // Get badge format
        $format = explode('|', $request->input('format'));
        $badge  = $this->badges[$format[1]];

        // Get inscriptions names and chunk data for rows per page
        $inscriptions = $this->inscription->getByColloque($request->input('colloque_id'),false,false);
        $colloque     = $this->colloque->find($request->input('colloque_id'));

        $exporter = new \App\Droit\Generate\Export\ExportBadge();
        $exporter->setConfig($badge);

        return $exporter->export($inscriptions, $colloque);
    }

    public function qrcodes($id)
    {
        $inscriptions = $this->inscription->getByColloque($id,false,false);
        $colloque     = $this->colloque->find($id);

        $exporter = new \App\Droit\Generate\Export\ExportQrcode();

        return $exporter->export($inscriptions, $colloque);
    }
}