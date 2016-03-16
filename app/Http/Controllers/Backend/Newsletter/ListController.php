<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use App\Droit\Newsletter\Repo\NewsletterListInterface;
use App\Droit\Newsletter\Repo\NewsletterEmailInterface;
use App\Droit\Newsletter\Worker\CampagneInterface;

use App\Droit\Helper\Helper;
use App\Http\Requests\SendTestRequest;

class ListController extends Controller
{
    protected $campagne;
    protected $worker;
    protected $helper;

    protected $list;
    protected $emails;

    public function __construct(NewsletterCampagneInterface $campagne, NewsletterListInterface $list, NewsletterEmailInterface $emails,CampagneInterface $worker, Helper $helper)
    {
        $this->campagne = $campagne;
        $this->list     = $list;
        $this->emails   = $emails;
        $this->worker   = $worker;
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
        $lists = $this->list->getAll();

        return view('backend.newsletter.lists.import')->with(['lists' => $lists]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lists = $this->list->getAll();
        $list  = $this->list->find($id);

        return view('backend.newsletter.lists.emails')->with(['lists' => $lists, 'list' => $list]);
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

}
