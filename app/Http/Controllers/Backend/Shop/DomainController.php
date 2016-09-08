<?php
namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDomain;
use App\Droit\Domain\Repo\DomainInterface;

class DomainController extends Controller
{
    protected $domain;

    public function __construct( DomainInterface $domain)
    {
        $this->domain = $domain;
    }

    /**
     * Show the form for creating a new resource.
     * GET /admin/domain/create
     *
     * @return Response
     */
    public function index()
    {
        $domains  = $this->domain->getAll();

        return view('backend.domains.index')->with(['domains' => $domains]);
    }

    /**
     * Show the form for creating a new resource.
     * GET /domain/create
     *
     * @return Response
     */
    public function create()
    {
        return view('backend.domains.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /domain
     *
     * @return Response
     */
    public function store(CreateDomain $request)
    {
        $domain = $this->domain->create( $request->all() );

        alert()->success('Collection crée');

        return redirect('admin/domain/'.$domain->id);
    }

    /**
     * Display the specified resource.
     * GET /domain/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $domain = $this->domain->find($id);

        return view('backend.domains.show')->with(['domain' => $domain]);
    }

    /**
     * Update the specified resource in storage.
     * PUT /domain/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,Request $request)
    {
        $this->domain->update( $request->all() );

        alert()->success('Collection mise à jour');

        return redirect('admin/domain/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /domain/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->domain->delete($id);

        alert()->success('Collection supprimée');

        return redirect()->back();
    }
}
