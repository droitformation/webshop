<?php

namespace App\Http\Controllers\Backend\Content;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\CreateBloc;
use App\Http\Controllers\Controller;

use App\Droit\Bloc\Repo\BlocInterface;
use App\Droit\Service\UploadInterface;
use App\Droit\Page\Repo\PageInterface;

class BlocController extends Controller
{
    protected $bloc;
    protected $upload;
    protected $page;

    public function __construct(BlocInterface $bloc, UploadInterface $upload , PageInterface $page)
    {
        $this->bloc    = $bloc;
        $this->upload  = $upload;
        $this->page    = $page;

        view()->share('positions', ['sidebar' => 'Barre latérale', 'page' => 'Dans page', 'footer' => 'Pied de page']);
    }

    /**
     * Display a listing of the resource.
     * GET /bloc
     *
     * @return Response
     */
    public function index($site)
    {
        $blocs = $this->bloc->getAll($site);

        return view('backend.bloc.index')->with(['blocs' => $blocs, 'current_site' => $site]);
    }

    /**
     * Show the form for creating a new resource.
     * GET /categorie/create
     *
     * @return Response
     */
    public function create($site)
    {
        return view('backend.bloc.create')->with(['current_site' => $site]);
    }

    /**
     * Store a newly created resource in storage.
     * POST /bloc
     *
     * @return Response
     */
    public function store(CreateBloc $request)
    {
        $data  = $request->except('file');

        // Image upload
        if($request->file('file', null)) {
            $file = $this->upload->upload( $request->file('file') , 'files/uploads');
            $data['image'] = $file['name'];
        }

        $bloc = $this->bloc->create( $data );

        event(new \App\Events\ContentUpdated());

        flash('Contenu crée')->success();

        return redirect('admin/bloc/'.$bloc->id);
    }

    /**
     * Display the specified resource.
     * GET /bloc/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $bloc = $this->bloc->find($id);
      
        return view('backend.bloc.show')->with(['bloc' => $bloc, 'current_site' => $bloc->site_id ]);
    }

    /**
     * Update the specified resource in storage.
     * PUT /bloc/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $data  = $request->except('file');
        $_file = $request->file('file', null);

        // Image upload
        if(isset($_file) )
        {
            $file = $this->upload->upload( $request->file('file') , 'files/uploads');

            $data['image'] = $file['name'];
        }

        $bloc = $this->bloc->update( $data );

        event(new \App\Events\ContentUpdated());

        flash('Contenu mis à jour')->success();

        return redirect('admin/bloc/'.$bloc->id);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /bloc
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->bloc->delete($id);

        flash('Contenu supprimée')->success();

        return redirect()->back();
    }

}
