<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use App\Droit\Newsletter\Repo\NewsletterTypesInterface;
use App\Droit\Newsletter\Repo\NewsletterContentInterface;
use App\Droit\Arret\Repo\GroupeInterface;
use App\Droit\Newsletter\Worker\CampagneInterface;
use App\Droit\Newsletter\Worker\MailjetInterface;

class CampagneController extends Controller
{
    protected $campagne;
    protected $content;
    protected $mailjet;
    protected $types;
    protected $groupe;
    protected $worker;
    protected $helper;

    public function __construct(NewsletterCampagneInterface $campagne, NewsletterContentInterface $content, GroupeInterface $groupe, MailjetInterface $mailjet, NewsletterTypesInterface $types, CampagneInterface $worker )
    {
        $this->campagne = $campagne;
        $this->content  = $content;
        $this->types    = $types;
        $this->groupe   = $groupe;
        $this->worker   = $worker;
        $this->mailjet  = $mailjet;
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

        $infos         = $this->campagne->find($id);
        $infos->newsletter->load('site');
        $campagne      = $this->worker->prepareCampagne($id);
        $categories    = $this->worker->getCategoriesArrets();
        $imgcategories = $this->worker->getCategoriesImagesArrets();

        return view('frontend.newsletter.view')->with(array('content' => $campagne , 'infos' => $infos , 'unsubscribe' => $unsubscribe , 'browser' => $browser, 'categories' => $categories, 'imgcategories' => $imgcategories));
    }

}
