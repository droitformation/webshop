<?php

namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests\CreateOrganisateur;
use App\Http\Requests\UpdateOrganisateur;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Organisateur\Repo\OrganisateurInterface;
use App\Droit\Service\UploadInterface;

class OrganisateurController extends Controller
{
    protected $organisateur;
    protected $upload;

    public function __construct(OrganisateurInterface $organisateur, UploadInterface $upload)
    {
        $this->organisateur = $organisateur;
        $this->upload       = $upload;
    }

    public function index()
    {
        $organisateurs = $this->organisateur->getAll();

        return view('backend.organisateurs.index')->with(['organisateurs' => $organisateurs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.organisateurs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrganisateur $request)
    {
        $data = $request->except('file');
        $_file = $request->file('file',null);

        if($_file)
        {
            $file = $this->upload->upload( $request->file('file') , 'files/logos', 'logo');

            $data['logo'] = $file['name'];
        }

        $organisateur = $this->organisateur->create($data);

        return redirect('admin/organisateur/'.$organisateur->id)->with(['status' => 'success' , 'message' => 'Organisateur crée']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $organisateur = $this->organisateur->find($id);

        if($request->ajax())
        {
            return response()->json( $organisateur, 200 );
        }

        return view('backend.organisateurs.show')->with(['organisateur' => $organisateur]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrganisateur $request, $id)
    {
        $data  = $request->except('file');
        $_file = $request->file('file',null);

        if($_file)
        {
            $file = $this->upload->upload( $request->file('file') , 'files/logos' , 'logo');
            $data['logo'] = $file['name'];
        }

        $organisateur = $this->organisateur->update($data);

        return redirect('admin/organisateur/'.$organisateur->id)->with(['status' => 'success' , 'message' => 'Organisateur mis à jour']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->organisateur->delete($id);

        return redirect()->back()->with(['status' => 'success', 'message' => 'Organisateur supprimée']);
    }

}
