<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Menu\Repo\MenuInterface;

class MenuController extends Controller
{
    protected $menu;

    public function __construct(MenuInterface $menu)
    {
        $this->menu = $menu;

        view()->share('positions', ['sidebar' => 'Barre latérale', 'main' => 'Principal', 'footer' => 'Pied de page', 'home' => 'Bloc page d\'accueil']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($site)
    {
        $menus = $this->menu->getAll($site);

        return view('backend.menus.index')->with(['menus' => $menus, 'current_site' => $site]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($site)
    {
        return view('backend.menus.create')->with(['current_site' => $site]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $menu = $this->menu->create($request->all());

        flash('Le menu a été crée')->success();

        return redirect('admin/menu/'.$menu->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $menu = $this->menu->find($id);

        return view('backend.menus.show')->with(['menu' => $menu, 'current_site' => $menu->site_id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $menu = $this->menu->update($request->all());

        flash('Le menu a été mis à jour')->success();

        return redirect('admin/menu/'.$menu->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->menu->delete($id);

        flash('Le menu a été supprimé')->success();

        return redirect('admin/menu');
    }
}
