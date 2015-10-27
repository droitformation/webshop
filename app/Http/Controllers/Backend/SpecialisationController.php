<?php namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Specialisation\Repo\SpecialisationInterface;

class SpecialisationController extends Controller {

    protected $colloque;
    protected $specialisation;

    public function __construct(ColloqueInterface $colloque, SpecialisationInterface $specialisation){

        $this->colloque       = $colloque;
        $this->specialisation = $specialisation;

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
                $data[] = ['label' => $result->title , 'value'   => $result->id];
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
        $colloque_id    = $request->input('colloque_id');
		$specialisation = $request->input('specialisation');
		$find           = $this->specialisation->search($specialisation);
				
		// If specialisation not found	
		if(!$find)
		{
			$find = $this->specialisation->create(['title' => $specialisation, 'colloque_id' => $colloque_id]);
		}

        $colloque = $this->colloque->find($colloque_id);
        $colloque->specialisations()->attach($find->id);

        if($request->ajax())
        {
            return response()->json( $find , 200 );
        }
	}
		
	public function destroy(Request $request)
	{
        $colloque_id    =  $request->input('colloque_id');
		$specialisation =  $request->input('specialisation');
        $find           = $this->specialisation->search($specialisation);

        $colloque   = $this->colloque->find($colloque_id);
        $colloque->specialisations()->detach($find->id);

        if($request->ajax())
        {
            return response()->json( $specialisation, 200 );
        }

	}


}