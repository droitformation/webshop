<?php

namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Shop\Attribute\Repo\AttributeInterface;

class AttributController extends Controller
{
    protected $attr;

    public function __construct( AttributeInterface $attr )
    {
        $this->attr = $attr;

        view()->share('duration',['week' => '1 semaine', 'month' => '1 mois', 'trimester' => '3 mois', 'semester' => '6 mois' ,'year' => '1 an']);
    }

    /**
     * Show the form for creating a new resource.
     * GET /admin/attribu/create
     *
     * @return Response
     */
    public function index()
    {
        $attributs = $this->attr->getAll();

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
        $attribut = $this->attr->create( $request->all() );

        flash('Attribut crée')->success();

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
        $attribut = $this->attr->find($id);

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
        $this->attr->update( $request->all() );

        flash('Attribut mise à jour')->success();

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
        $attribu = $this->attr->delete($request->input('id'));

        flash('Attribut supprimée')->success();

        return redirect()->back();
    }
}