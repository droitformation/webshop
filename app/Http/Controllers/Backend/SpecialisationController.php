<?php namespace App\Http\Controllers\Backend;

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
	
	public function store(Request $request)
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

        if($request->ajax())
        {
            return response()->json( $find , 200 );
        }
	}
		
	public function destroy(Request $request)
	{
        $id             = $request->input('id');
		$specialisation = $request->input('specialisation');
        $model          = $request->input('model');
        $find           = $this->specialisation->search($specialisation);

        $item   = $this->$model->find($id);
        $item->specialisations()->detach($find->id);

        if($request->ajax())
        {
            return response()->json( $specialisation, 200 );
        }
	}


}