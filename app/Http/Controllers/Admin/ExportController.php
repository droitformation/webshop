<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class ExportController extends Controller
{
    protected $inscription;
    protected $colloque;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ColloqueInterface $colloque, InscriptionInterface $inscription )
    {
        $this->inscription = $inscription;
        $this->colloque    = $colloque;
        $this->helper      = new \App\Droit\Helper\Helper();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function inscription($id)
    {
        \Excel::create('Export inscriptions', function($excel) use ($id) {

            $inscriptions = $this->inscription->getByColloque($id);
            $colloque     = $this->colloque->find($id);

            $excel->sheet('Export', function($sheet) use ($inscriptions,$colloque) {

                $sheet->setOrientation('landscape');
                $sheet->loadView('export.inscription', ['inscriptions' => $inscriptions, 'colloque' => $colloque]);

            });

        })->export('xls');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
