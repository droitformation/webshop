<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Arret\Repo\GroupeInterface;

class CampagneController extends Controller
{
    protected $campagne;
    protected $worker;
    protected $helper;

    public function __construct(NewsletterCampagneInterface $campagne, CampagneInterface $worker )
    {
        $this->campagne = $campagne;
        $this->worker   = $worker;
        $this->helper   = new \App\Droit\Helper\Helper();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campagnes = $this->campagne->getAll();

        return view('backend.newsletter.campagne.index')->with(compact('campagnes'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /*
        * Urls
        */
        $unsubscribe  = url('/unsubscribe/'.$id);
        $browser      = url('/campagne/'.$id);

        $infos = $this->campagne->find($id);
        $infos->newsletter->load('site');
        
        $campagne      = $this->worker->prepareCampagne($id);
        $categories    = $this->worker->getCategoriesArrets();
        $imgcategories = $this->worker->getCategoriesImagesArrets();

        return view('frontend.newsletter.view')->with(array('content' => $campagne , 'infos' => $infos , 'unsubscribe' => $unsubscribe , 'browser' => $browser, 'categories' => $categories, 'imgcategories' => $imgcategories));
    }

}
