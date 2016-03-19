<?php namespace App\Http\Controllers\Backend\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Specialisation\Repo\SpecialisationInterface;

class SpecialisationController extends Controller {

    protected $colloque;
    protected $specialisation;
    protected $adresse;

    public function __construct(ColloqueInterface $colloque, SpecialisationInterface $specialisation, AdresseInterface $adresse)
    {
        $this->colloque       = $colloque;
        $this->specialisation = $specialisation;
        $this->adresse        = $adresse;
	}

	/**
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $data = [];

		$specialisations = $this->specialisation->getAll();

        if(!$specialisations->isEmpty())
        {
            foreach($specialisations as $result)
            {
                $data[] = $result->title;
            }
        }

        if($request->ajax())
        {
            return response()->json( $data, 200 );
        }

        return view('backend.specialisations.index')->with(['specialisations' => $specialisations]);
	}

    public function search(Request $request)
    {
        $data = [];
        $term = $request->input('term');

        $specialisation = $this->specialisation->search($term,true);

        if(!$specialisation->isEmpty())
        {
            foreach($specialisation as $result)
            {
                $data[] = ['label' => $result->title, 'value' => $result->id];
            }
        }

        if($request->ajax())
        {
            return response()->json( $data, 200 );
        }
    }

    public function create()
    {
        return view('backend.specialisations.create');
    }

    public function show($id)
    {
        $specialisation = $this->specialisation->find($id);

        return view('backend.specialisations.show')->with(['specialisation' => $specialisation]);
    }

    public function update(Request $request)
    {
        $specialisation = $this->specialisation->update($request->all());

        return redirect('admin/specialisation')->with(['status' => 'success' , 'message' => 'Spécialisations mise à jour']);
    }
	
	public function store(Request $request)
	{
        if($request->ajax())
        {
            $id             = $request->input('id');
            $specialisation = $request->input('specialisation');
            $model          = $request->input('model');
            $find           = $this->specialisation->search($specialisation);

            // If specialisation not found
            if(!$find)
            {
                $find = $this->specialisation->create(['title' => $specialisation, $model.'_id' => $id]);
            }

            $item = $this->$model->find($id);
            $item->specialisations()->attach($find->id);

            return response()->json( $find , 200 );
        }

        $specialisation = $this->specialisation->create($request->all());

        return redirect('admin/specialisation')->with(['status' => 'success' , 'message' => 'Spécialisations crée']);
	}
		
	public function destroy(Request $request)
	{
        $id = $request->input('id');

        if($request->ajax())
        {
            $specialisation = $request->input('specialisation');
            $model          = $request->input('model');
            $find           = $this->specialisation->search($specialisation);

            $item   = $this->$model->find($id);
            $item->specialisations()->detach($find->id);

            return response()->json( $specialisation, 200 );
        }

        $this->specialisation->delete($id);

        return redirect('admin/specialisation')->with(['status' => 'success' , 'message' => 'Spécialisations supprimé']);
	}


}