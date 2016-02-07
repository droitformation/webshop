<?php

namespace App\Http\Controllers\Backend\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Pays\Repo\PaysInterface;
use App\Droit\Canton\Repo\CantonInterface;
use App\Droit\Profession\Repo\ProfessionInterface;

use App\Droit\User\Repo\DuplicateInterface;
use App\Http\Requests\CreateDuplicate;
use App\Http\Requests\UpdateDuplicate;

class DuplicateController extends Controller {

    protected $duplicate;
    protected $pays;
    protected $canton;
    protected $profession;

    public function __construct(DuplicateInterface $duplicate, CantonInterface $canton, PaysInterface $pays, ProfessionInterface $profession)
    {
        $this->duplicate  = $duplicate;
        $this->pays       = $pays;
        $this->canton     = $canton;
        $this->profession = $profession;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $duplicates = $this->duplicate->getPaginate();

        return view('backend.duplicates.index')->with(['duplicates' => $duplicates]);
    }

    public function duplicates(Request $request)
    {
        $order  = $request->input('order');
        $search = $request->input('search',null);
        $search = ($search ? $search['value'] : null);

        return $this->duplicate->get_ajax(
            $request->input('draw'), $request->input('start'), $request->input('length'), $order[0]['column'], $order[0]['dir'], $search
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $cantons     = $this->canton->getAll();
        $professions = $this->profession->getAll();
        $pays        = $this->pays->getAll();

        return view('backend.duplicates.create')->with(compact('pays','cantons','professions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $duplicate = $this->duplicate->create($request->all());

        return redirect('admin/duplicate/'.$duplicate->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id, Request $request)
    {
        $duplicate = $this->duplicate->find($id);

        return view('backend.duplicates.show')->with(compact('duplicate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,Request $request)
    {
        $duplicate = $this->duplicate->update($request->all());

        $request->ajax();

        return redirect('admin/duplicate/'.$duplicate->id)->with(array('status' => 'success', 'message' => 'Utilisateur mis à jour' ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->duplicate->delete($id);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'Utilisateur supprimé' ));
    }

}
