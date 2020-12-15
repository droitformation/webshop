<?php namespace App\Http\Controllers\Backend\Sondage;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Droit\Sondage\Repo\ModeleInterface;
use App\Droit\Sondage\Repo\AvisInterface;

class ModeleController extends Controller
{
    protected $modele;
    protected $avis;

    public function __construct(ModeleInterface $modele, AvisInterface $avis)
    {
        $this->modele = $modele;
        $this->avis   = $avis;
    }

    public function index()
    {
        $modeles = $this->modele->getAll();

        return view('backend.modeles.index')->with(['modeles' => $modeles]);
    }

    public function create()
    {
        return view('backend.modeles.create');
    }

    public function store(Request $request)
    {
        $modele = $this->modele->create($request->all());

        flash('Le modelee a été crée')->success();

        return redirect('admin/modele/'.$modele->id);
    }

    public function show($id)
    {
        $modele = $this->modele->find($id);

        $avis = $this->avis->getAll(true);
        $avis = $avis->map(function ($row, $key) {
            return \App\Droit\Sondage\Entities\Transform::make($row, $key);
        })->sortBy('alpha')->values();

        list($hidden, $activ) = $avis->partition(function ($item) {
            return $item->hidden;
        });

        return view('backend.modeles.show')->with(['modele' => $modele, 'avis' => $activ]);
    }

    public function update(Request $request, $id)
    {
        $avis = collect($request->input('avis'))->mapWithKeys(function($item,$key) {
            return [$item['id'] => ['rang' => $item['rang']]];
        })->toArray();

        $modele = $this->modele->update($request->only(['id','title','description']) + ['avis' => $avis]);

        if($request->ajax()){
            return response()->json(['status' => true]);
        }

        flash('Le modele a été mis à jour')->success();

        return redirect('admin/modele/'.$id);
    }

    public function destroy($id)
    {
        $this->avis->delete($id);

        flash('Le modele a été supprimé')->success();

        return redirect()->back();
    }
}
