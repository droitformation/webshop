<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteSubscriberRequest;

use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Newsletter\Repo\NewsletterUserInterface;
use App\Droit\Newsletter\Worker\MailjetServiceInterface;

use App\Http\Requests\RemoveNewsletterUserRequest;
use App\Droit\Newsletter\Worker\SubscriptionWorkerInterface;

class SubscriberController extends Controller
{
    protected $subscriber;
    protected $newsletter;
    protected $worker;
    protected $subscription_worker;

    public function __construct(NewsletterUserInterface $subscriber, NewsletterInterface $newsletter, MailjetServiceInterface $worker, SubscriptionWorkerInterface $subscription_worker)
    {
        $this->subscriber = $subscriber;
        $this->newsletter = $newsletter;
        $this->worker     = $worker;
        $this->subscription_worker = $subscription_worker;

        view()->share('isNewsletter',true);
    }

    /**
     * Display a listing of the resource.
     * GET /subscriber
     *
     * @return Response
     */
    public function index()
    {
        return view('backend.newsletter.subscribers.index');
    }

    /**
     * Display a listing of tsubscribers for ajax
     * GET /subscriber/getAllAbos
     *
     * @return Response
     */
    public function subscribers(Request $request)
    {
        $order  = $request->input('order');
        $search = $request->input('search',null);
        $search = ($search ? $search['value'] : null);

        return $this->subscriber->get_ajax(
            $request->input('draw'), $request->input('start'), $request->input('length'), $order[0]['column'], $order[0]['dir'], $search
        );
    }

    /**
     * Show the form for creating a new resource.
     * GET /subscriber/create
     *
     * @return Response
     */
    public function create()
    {
        $newsletter = $this->newsletter->getAll();

        return view('backend.newsletter.subscribers.create')->with(['newsletter' => $newsletter ]);
    }

    /**
     * Store a newly created resource in storage.
     * POST /subscriber
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // Subscribe user with activation token to website list and sync newsletter abos
        $subscriber = $this->subscriber->create(['email' => $request->input('email'), 'activated_at' => \Carbon\Carbon::now() ]);

        $this->subscription_worker->subscribe($subscriber,[$request->input('newsletter_id')]);

        alert()->success('Abonné ajouté');

        return redirect('build/subscriber');
    }

    /**
     * Show the form for editing the specified resource.
     * GET /subscriber/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $subscriber = $this->subscriber->find($id);
        $newsletter = $this->newsletter->getAll();

        return view('backend.newsletter.subscribers.show')->with(['subscriber' => $subscriber , 'newsletter' => $newsletter]);
    }

    /**
     * Update the specified resource in storage.
     * PUT /subscriber/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update(RemoveNewsletterUserRequest $request, $id)
    {
        $subscriber = $this->subscriber->update(['id' => $id, 'email' => $request->input('email'),'activated_at' => $request->input('activation') ? date('Y-m-d G:i:s') : null]);

        $new = $request->input('newsletter_id');
        $has = $subscriber->subscriptions->pluck('id')->all();

        $added   = array_diff($new, $has);
        $removed = array_diff(array_unique(array_merge($new, $has)), $new);

        $this->subscription_worker->subscribe($subscriber,$added);
        $this->subscription_worker->unsubscribe($subscriber,$removed);

        alert()->success('Abonné édité');

        return redirect('build/subscriber/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /subscriber/{id}
     *
     * @param  int  $email
     * @return Response
     */
    public function destroy($id, DeleteSubscriberRequest $request)
    {
        // find the abo
        $subscriber  = $this->subscriber->findByEmail($request->input('email'));
        $newsletters = $this->newsletter->getAll();

        $this->subscription_worker->unsubscribe($subscriber,$newsletters->pluck('id')->all());

        alert()->success('Abonné supprimé');

        return redirect('build/subscriber');
    }
}
