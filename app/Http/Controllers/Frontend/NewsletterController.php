<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use App\Droit\Newsletter\Worker\CampagneInterface;


class NewsletterController extends Controller
{
    protected $newsletter;
    protected $campagne;
    protected $worker;

    public function __construct(NewsletterInterface $newsletter, NewsletterCampagneInterface $campagne, CampagneInterface $worker)
    {
        $this->campagne   = $campagne;
        $this->worker     = $worker;
        $this->newsletter = $newsletter;

        $newsletters = $this->newsletter->getAll();

        view()->share('newsletters', $newsletters);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $newsletter = $this->newsletter->find($id);

        return view('frontend.newsletter.newsletter')->with(['newsletter' => $newsletter]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function campagne($id)
    {
        $campagne      = $this->campagne->find($id);
        $content       = $this->worker->prepareCampagne($id);
        $categories    = $this->worker->getCategoriesArrets();
        $imgcategories = $this->worker->getCategoriesImagesArrets();

        return view('frontend.newsletter.campagne')->with(
            ['campagne' => $campagne , 'content' => $content, 'categories' => $categories, 'imgcategories' => $imgcategories]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
