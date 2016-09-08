<?php

namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Shop\Attribute\Repo\AttributeInterface;

class AttributController extends Controller
{
    protected $attribu;

    public function __construct( AttributeInterface $attribu )
    {
        $this->attribu = $attribu;

        view()->share('intervals',['week' => '1 semaine', 'month' => '1 mois', 'trimester' => '3 mois', 'semester' => '6 mois' ,'year' => '1 an']);
    }

    /**
     * Show the form for creating a new resource.
     * GET /admin/attribu/create
     *
     * @return Response
     */
    public function index()
    {
        $attributs = $this->attribu->getAll();

        return view('backend.attributs.index')->with(['attributs' => $attributs]);
    }

    /**
     * Show the form for creating a new resource.
     * GET /attribu/create
     *
     * @return Response
     */
    public function create()
    {
        return view('backend.attributs.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /attribu
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $attribut = $this->attribu->create( $request->all() );

        alert()->success('Attribut crée');

        return redirect('admin/attribut');
    }

    /**
     * Display the specified resource.
     * GET /attribu/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $attribut = $this->attribu->find($id);

        return view('backend.attributs.show')->with(['attribut' => $attribut]);
    }

    /**
     * Update the specified resource in storage.
     * PUT /attribu/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,Request $request)
    {
        $this->attribu->update( $request->all() );

        alert()->success('Attribut mise à jour');

        return redirect('admin/attribut/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /attribu/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');

        $attribu = $this->attribu->find($id);
        $attribu->delete($id);

        alert()->success('Attribut supprimée');

        return redirect()->back();
    }
}