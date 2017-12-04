<?php

namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Shop\Author\Repo\AuthorInterface;

class ShopAuthorController extends Controller
{
    protected $author;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthorInterface $author)
    {
        $this->author = $author;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $shopauthors = $this->author->getAll();

        return view('backend.shopauthors.index')->with(['shopauthors' => $shopauthors]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backend.shopauthors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $exist = $this->author->findByName($request->input('first_name'),$request->input('last_name'));

        if($exist){
            alert()->danger('<b>Attention</b> Cet auteur existe déjà!!');

            return redirect()->back()->withInput();
        }

        $shopauthor = $this->author->create($request->all());

        alert()->success('Auteur crée');

        return redirect('admin/shopauthor/'.$shopauthor->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $shopauthor = $this->author->find($id);

        return view('backend.shopauthors.show')->with(['shopauthor' => $shopauthor]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $shopauthor = $this->author->update($request->all());

        alert()->success('Auteur mis à jour');

        return redirect('admin/shopauthor/'.$shopauthor->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->author->delete($id);

        alert()->success('Auteur supprimé');

        return redirect('admin/shopauthor');
    }
}
