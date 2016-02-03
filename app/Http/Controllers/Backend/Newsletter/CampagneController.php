<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Newsletter\Repo\NewsletterTypesInterface;
use App\Droit\Newsletter\Repo\NewsletterContentInterface;
use App\Droit\Arret\Repo\GroupeInterface;
use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use App\Droit\Newsletter\Worker\CampagneInterface;
use App\Droit\Newsletter\Worker\MailjetInterface;
use App\Droit\Helper\Helper;
use App\Http\Requests\SendTestRequest;

class CampagneController extends Controller
{
    protected $campagne;
    protected $worker;
    protected $content;
    protected $mailjet;
    protected $types;
    protected $groupe;
    protected $helper;

    public function __construct(
        NewsletterCampagneInterface $campagne,
        NewsletterContentInterface $content,
        GroupeInterface $groupe,
        MailjetInterface $mailjet,
        NewsletterTypesInterface $types,
        CampagneInterface $worker,
        Helper $helper
    )
    {
        $this->campagne = $campagne;
        $this->worker   = $worker;
        $this->content  = $content;
        $this->types    = $types;
        $this->groupe   = $groupe;
        $this->mailjet  = $mailjet;
        $this->helper   = $helper;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
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
     * Show the form for creation a campagne for newsletter.
     * GET /admin/campagne/create/{newsletter}
     *
     * @return \Illuminate\Http\Response
     */
    public function create($newsletter)
    {
        return view('backend.newsletter.campagne.create')->with(['newsletter' => $newsletter]);
    }

    /**
     * Show the form for editing the campagne.
     * GET /admin/campagne/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $campagne = $this->campagne->find($id);

        return view('backend.newsletter.campagne.edit')->with(array( 'campagne' => $campagne ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campagne = $this->campagne->create( ['sujet' => $request->input('sujet'), 'auteurs' => $request->input('auteurs'), 'newsletter_id' => $request->input('newsletter_id') ] );

        $this->mailjet->setList($campagne->newsletter->list_id);

        $created  = $this->mailjet->createCampagne($campagne);

        if(!$created)
        {
            throw new \App\Exceptions\CampagneCreationException('Problème avec la création de campagne sur mailjet');
        }

        return redirect('admin/campagne/'.$campagne->id)->with( array('status' => 'success' , 'message' => 'Campagne crée') );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blocs         = $this->types->getAll();
        $infos         = $this->campagne->find($id);
        $campagne      = $this->worker->prepareCampagne($id);

        $categories    = $this->worker->getCategoriesArrets();
        $imgcategories = $this->worker->getCategoriesImagesArrets();

        return view('backend.newsletter.campagne.show')->with(
            ['isNewsletter' => true, 'campagne' => $campagne , 'infos' => $infos, 'blocs' => $blocs, 'categories' => $categories, 'imgcategories' => $imgcategories]
        );
    }

    /**
     * Send campagne
     *
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        // Get campagne
        $campagne = $this->campagne->find($request->id);

        //set or update html
        $html = $this->worker->html($campagne->id);

        // Sync html content to api service and send!
        $this->mailjet->setHtml($html,$campagne->api_campagne_id);

        $result = $this->mailjet->sendCampagne($campagne->api_campagne_id,$campagne->id);

        if(!$result)
        {
            throw new \App\Exceptions\CampagneSendException('Problème avec l\'envoi');
        }

        // Update campagne status
        $campagne->status     = 'envoyé';
        $campagne->updated_at = date('Y-m-d G:i:s');

        $campagne->save();

        return redirect('admin/newsletter')->with(['status' => 'success' , 'message' => 'Campagne envoyé!']);

    }

    /**
     * Send test campagne
     *
     * @return \Illuminate\Http\Response
     */
    public function test(SendTestRequest $request)
    {
        $id       = $request->input('id');
        $campagne = $this->campagne->find($id);
        $sujet    = ($campagne->status == 'brouillon' ? 'TEST | '.$campagne->sujet : $campagne->sujet );

        // GET html
        $html = $this->worker->html($campagne->id);

        // Send the email
        $this->mailjet->sendTest($request->input('email'),$html,$sujet);

        // If we want to send via ajax just add a send_type "ajax
        $ajax = $request->input('send_type', 'normal');

        if($ajax == 'ajax'){
            echo 'ok'; exit;
        }

        return redirect('admin/campagne/'.$campagne->id)->with( ['status' => 'success' , 'message' => 'Email de test envoyé!'] );
    }


    /**
     * Add bloc to newsletter
     * POST data
     * @return Response
     */
    public function process(Request $request){

        $data = $request->all();

        // image resize
        if(isset($data['image']) && !empty($data['image']))
        {
            $this->helper->resizeImage($data['image'],$data['type_id']);
        }

        $this->content->create($data);

        return redirect('admin/campagne/'.$data['campagne'].'#componant')->with(['status' => 'success', 'message' => 'Bloc ajouté' ]);

    }

    /**
     * Edit bloc from newsletter
     * POST data
     * @return Response
     */
    public function editContent(Request $request){

        $contents = $this->content->update($request->all());

        return redirect('admin/campagne/'.$contents->newsletter_campagne_id.'#componant')->with(array('status' => 'success', 'message' => 'Bloc édité' ));
    }

    /**
     * Sorting bloc newsletter
     * POST remove
     * AJAX
     * @return Response
     */
    public function sorting(Request $request){

        $data = $request->all();

        $contents = $this->content->updateSorting($data['bloc_rang']);

        print_r($data);

    }

    /**
     * Sorting bloc newsletter
     * POST remove
     * AJAX
     * @return Response
     */
    public function sortingGroup(Request $request){

        $data = $request->all();

        $groupe_rang = $data['groupe_rang'];
        $groupe_id   = $data['groupe_id'];

        $arrets = $this->helper->prepareCategories($groupe_rang);

        $groupe = $this->groupe->find($groupe_id);
        $groupe->arrets_groupes()->sync($arrets);

        print_r($groupe);

    }

    /**
     * Campagne
     * AJAX
     * @return Response
     */
    public function simple($id){

        return $this->campagne->find($id);
    }

    /**
     * Remove bloc from newsletter
     * POST remove
     * AJAX
     * @return Response
     */
    public function remove(Request $request){

        $this->content->delete($request->input('id'));

        return 'ok';
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
        $campagne = $this->campagne->update(['id' => $request->input('id'), 'sujet' => $request->input('sujet'), 'auteurs' => $request->input('auteurs')] );

        return redirect('admin/campagne/'.$campagne->id)->with( array('status' => 'success' , 'message' => 'Campagne édité') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campagne = $this->campagne->find($id);
        $campagne->content()->delete();
        $this->campagne->delete($id);

        return redirect()->back()->with(array('status' => 'success', 'message' => 'Campagne supprimée' ));
    }
}
