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
    protected $export_inscription;
    protected $export_adresse;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ColloqueInterface $colloque, InscriptionInterface $inscription, AdresseInterface $adresse)
    {
        $this->inscription = $inscription;
        $this->colloque    = $colloque;
        $this->adresse     = $adresse;

        $this->label = new \App\Droit\Helper\Label();

        $this->export_inscription = new \App\Droit\Generate\Excel\ExcelInscription();
        $this->export_adresse     = new \App\Droit\Generate\Excel\ExcelAdresse();
        $this->export_badge       = new \App\Droit\Generate\Pdf\PdfBadge();

        $this->badges = config('badge');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function inscription(Request $request)
    {
        $colloque     = $this->colloque->find($request->input('id'));
        $inscriptions = $this->inscription->getByColloque($colloque->id);

        $this->export_inscription->exportInscription($inscriptions,$colloque, $request->input('columns', config('columns.names')) , $request->input('sort', false));
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

    public function generate(Request $request)
    {
        $terms    = $request->session()->get('terms');
        $each     = (isset($terms['each']) ? true : false);

        // Get adresses with terms
        $adresses = $this->adresse->searchMultiple($terms, $each);

        // Export adresses
        $this->export_adresse->exportAdresse($adresses);
    }

    public function badges(Request $request)
    {
        // Get badge format
        $format = explode('|', $request->input('format'));
        $badge  = $this->badges[$format[1]];

        // Get inscriptions names and chunk data for rows per page
        $inscriptions = $this->inscription->getByColloque($request->input('colloque_id'),false,false);

        return $this->export_badge->export($request->input('colloque_id'), $inscriptions, $badge);
    }
}