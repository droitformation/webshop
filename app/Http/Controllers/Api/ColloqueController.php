<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Colloque\Repo\ColloqueInterface;

class ColloqueController extends Controller {
    
	protected $colloque;
    
	public function __construct(ColloqueInterface $colloque)
	{
        $this->colloque = $colloque;
	}
    
	public function index()
	{
        return $this->colloque->getCurrent();
	}
    
    public function show($id)
    {
        $colloque = $this->colloque->find($id);

		return [
			'id'           => $colloque->id,
			'titre'        => $colloque->titre,
			'sujet'        => $colloque->sujet,
			'soustitre'    => strip_tags($colloque->soustitre),
			'remarques'    => $colloque->remarques,
			'illustration' => $colloque->frontend_illustration,
			'organisateur' => $colloque->organisateur,
			'location'     => $colloque->location ? $colloque->location->name : '',
			'date'         => $colloque->event_date,
			'registration' => utf8_encode($colloque->registration_at->formatLocalized('%d %B %Y')),
			'link'         => url('pubdroit/colloque/').$colloque->id
		];
    }

	public function event(Request $request)
	{
        $archived  = $request->input('archive') ? true : false;
		$centres   = $request->input('centres',[]);

        $colloques = $this->colloque->eventList($request->input('centres',[]), $archived, $request->input('name',null));

        $colloques = $colloques->map(function ($colloque, $key) use ($centres){

            $organisateur = $colloque->centres->filter(function ($center, $key) use ($centres) {
                return in_array($center->id,$centres);
            })->map(function ($center) {
                return $center->id == 2 ? 'cemaj' : 'cert';
            });
            
            if($colloque->location){
                $location = $colloque->location->name .', '.strip_tags($colloque->location->adresse);
            }

            return [
                'url'          => url('pubdroit/colloque/'.$colloque->id),
                'event'        => $colloque->toArray(),
                'prix'         => $colloque->prices_active->toArray(),
                'location'     => isset($location) ? $location : '',
                'programme'    => isset($colloque->programme) ? url('files/colloques/programme/'.$colloque->programme->path) : '',
                'organisateur' => $organisateur->toArray(),
            ];
        });

		return response()->json(['data' => $colloques->toArray()]);
	}
	
}
