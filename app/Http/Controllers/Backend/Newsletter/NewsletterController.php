<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use App\Droit\Newsletter\Worker\MailjetServiceInterface;
use App\Droit\Newsletter\Repo\NewsletterListInterface;

use App\Droit\Service\UploadWorker;

class NewsletterController extends Controller
{
    protected $newsletter;
    protected $campagne;
    protected $upload;
    protected $mailjet;
    protected $list;

    public function __construct(NewsletterInterface $newsletter, UploadWorker $upload, MailjetServiceInterface $mailjet, NewsletterListInterface $list, NewsletterCampagneInterface  $campagne)
    {
        $this->campagne   = $campagne;
        $this->newsletter = $newsletter;
        $this->upload     = $upload;
        $this->mailjet    = $mailjet;
        $this->list       = $list;

        setlocale(LC_ALL, 'fr_FR.UTF-8');

        view()->share('isNewsletter',true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newsletters = $this->newsletter->getAll();
        $listes      = $this->list->getAll();

        return view('backend.newsletter.template.index')->with(['newsletters' => $newsletters, 'listes' => $listes]);
    }

    public function archive($newsletter_id,$year)
    {
        $newsletter = $this->newsletter->find($newsletter_id);
        $campagnes  = $this->campagne->getArchives($newsletter_id,$year);
        $listes     = $this->list->getAll();

        return view('backend.newsletter.template.archive')->with(['newsletter' => $newsletter, 'campagnes' => $campagnes, 'year' => $year, 'listes' => $listes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lists = $this->mailjet->getAllLists();

        return view('backend.newsletter.template.create')->with(['lists' => $lists]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('logos','header','soutien');

        if(!empty($request->file('logos'))){
            $logos = $this->upload->upload($request->file('logos'), 'newsletter');
            $data['logos'] = isset($logos['name']) ?  $logos['name'] : null;
        }

        if(!empty($request->file('header'))){
            $header = $this->upload->upload($request->file('header'), 'newsletter');
            $data['header'] = isset($header['name']) ? $header['name'] : null;
        }

        if(!empty($request->file('soutien'))){
            $soutien = $this->upload->upload($request->file('soutien'), 'newsletter');
            $data['soutien'] = isset($soutien['name']) ? $soutien['name'] : null;
        }

        $newsletter = $this->newsletter->create($data);

        flash('Newsletter crée')->success();

        return redirect('build/newsletter/'.$newsletter->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lists      = $this->mailjet->getAllLists();
        $newsletter = $this->newsletter->find($id);

        return view('backend.newsletter.template.show')->with(['newsletter' => $newsletter, 'lists' => $lists]);
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
        $data = $request->except('logos','header','soutien');

        if(!empty($request->file('logos'))){
            $logos = $this->upload->upload($request->file('logos'), 'newsletter');
            $data['logos'] = isset($logos['name']) ?  $logos['name'] : null;
        }
        
        if(!empty($request->file('header'))){
            $header = $this->upload->upload($request->file('header'), 'newsletter');
            $data['header'] = isset($header['name']) ? $header['name'] : null; 
        }
        
        if(!empty($request->file('soutien'))){
            $soutien = $this->upload->upload($request->file('soutien'), 'newsletter');
            $data['soutien'] = isset($soutien['name']) ? $soutien['name'] : null; 
        }

        $newsletter = $this->newsletter->update($data);

        flash('Newsletter édité')->success();

        return redirect('build/newsletter/'.$newsletter->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->newsletter->delete($id);

        flash('Newsletter supprimée')->success();

        return redirect()->back();
    }
}
