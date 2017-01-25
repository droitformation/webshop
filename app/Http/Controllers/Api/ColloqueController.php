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
}
