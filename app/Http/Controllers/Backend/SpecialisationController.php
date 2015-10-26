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
		$specialisations = $this->specialisation->getAll();

        if($request->ajax())
        {
            return response()->json( $specialisations, 200 );
        }
	}

    public function search(Request $request)
    {
        $term = $request->input('term');
        $tags = $this->specialisation->search($term);

        if($request->ajax())
        {
            return \Response::json( $tags, 200 );
        }
    }
	
	public function store(Request $request)
	{
		$id   = $request->input('id');
		$specialisation  = $request->input('specialisation');
		$find = $this->specialisation->search($specialisation);
				
		// If specialisation not found	
		if(!$find)
		{
			$find = $this->specialisation->create(['title' => $specialisation, 'colloque_id' => $id]);
		}

        $colloque = $this->colloque->find($id);
        $colloque->specialisations()->attach($find->id);

        if($request->ajax())
        {
            return response()->json( $find , 200 );
        }
	}
		
	public function destroy(Request $request)
	{
		$id   =  $request->input('id');
		$specialisation  =  $request->input('specialisation');

        $colloque   = $this->colloque->find($id);
        $colloque->pecialisations()->detach($specialisation->id);

        if($request->ajax())
        {
            return response()->json( $specialisation, 200 );
        }

	}


}