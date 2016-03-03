<?php

namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\CreateAttestation;
use App\Http\Controllers\Controller;
use App\Droit\Colloque\Repo\AttestationInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;

class AttestationController extends Controller
{
    protected $attestation;
    protected $colloque;

    public function __construct(AttestationInterface $attestation, ColloqueInterface $colloque)
    {
        $this->attestation  = $attestation;
        $this->colloque     = $colloque;
    }

    /**
     *
     * @param  int  $id
     * @return Response
     */
    public function colloque($id)
    {
        $colloque = $this->colloque->find($id);

        return view('backend.attestations.create')->with(['colloque' => $colloque]);
    }

    /**
     * Store a newly created resource in storage.
     * POST /attestation
     *
     * @return Response
     */
    public function store(CreateAttestation $request)
    {
        $attestation = $this->attestation->create( $request->all() );

        return redirect('admin/attestation/'.$attestation->id)->with(['status' => 'success' , 'message' => 'Attestation crée']);
    }

    /**
     * Display the specified resource.
     * GET /attestation/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $attestation = $this->attestation->find($id);

        return view('backend.attestations.show')->with(['attestation' => $attestation]);
    }

    /**
     * Update the specified resource in storage.
     * PUT /attestation/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, CreateAttestation $request)
    {
        $this->attestation->update( $request->all() );

        return redirect('admin/attestation/'.$id)->with(['status' => 'success' , 'message' => 'Attestation mise à jour']);
    }
}
