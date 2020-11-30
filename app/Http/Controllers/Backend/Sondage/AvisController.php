<?php

namespace App\Http\Controllers\Backend\Sondage;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Sondage\Repo\AvisInterface;
use App\Droit\Sondage\Repo\SondageInterface;
use function GuzzleHttp\Psr7\str;

class AvisController extends Controller
{
    protected $avis;
    protected $sondage;

    public function __construct(AvisInterface $avis, SondageInterface $sondage)
    {
        $this->avis = $avis;
        $this->sondage = $sondage;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort','alpha');
        $avis = $this->avis->getAll();

        list($hidden, $activ) = $avis->partition(function ($item) {
            return $item->hidden;
        });

        $hidden = $hidden->map(function ($row, $key) {
            $sort = preg_replace('/[^a-z]/i', '', trim(strip_tags($row->question)));
            $row->setAttribute('alpha',strtolower($sort));
            return $row;
        })->sortBy($sort)->values();

        $activ = $activ->map(function ($row) {
            $sort = preg_replace('/[^a-z]/i', '', trim(strip_tags($row->question)));

            return [
                'id'        => $row->id,
                'type_name' => $row->type_name,
                'type'      => $row->type,
                'question'  => strip_tags($row->question),
                'hidden'    => $row->hidden,
                'class'     => $row->hidden ? 'row-hidden' : '',
                'alpha'     => strtolower($sort),
                'path_delete' => secure_url('admin/avis/deleteAjax'),
                'path_update' => secure_url('admin/avis/updateAjax'),
            ];
        })->sortBy($sort)->values();

        return view('backend.avis.index')->with(['avis' => $activ, 'hidden' => $hidden, 'sort' => $sort]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $avis = $this->avis->getAll();

        $examples = $avis->map(function ($avi, $key) {
            
            $choices = trim($avi->choices);
            $choices = array_map('trim',explode(',',$choices));
            $choices = implode(',',$choices);

            return $avi->type == 'radio' ?  $choices: '';
        })->reject(function ($value, $key) {
            return empty($value);
        })->unique();

        return view('backend.avis.create')->with(['examples' => $examples]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $avis = $this->avis->create($request->all());

        flash('La question a été crée')->success();

        return redirect('admin/avis');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $avis = $this->avis->find($id);

        return view('backend.avis.show')->with(['avis' => $avis]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $avis = $this->avis->update($request->all());

        flash('La question a été mis à jour')->success();

        return redirect()->back();
    }

    public function destroy($id)
    {
        $this->avis->delete($id);

        flash('La question a été supprimé')->success();

        return redirect()->back();
    }

    public function deleteAjax(Request $request)
    {
        $this->avis->delete($request->input('id'));

        return response()->json($this->avis->getAll());
    }

    public function updateAjax(Request $request)
    {
        $avis = $this->avis->update(['id' => $request->input('id'), 'hidden' => 1]);

        return response()->json($avis);
    }
}
