<?php namespace App\Droit\Newsletter\Worker;

class CleanWorker{

    protected $worker;
    protected $newsletter;
    protected $mailjet;
    protected $list_id;
    protected $newsletter_id;

    protected $name = '';
    public $subscribers;

    public function __construct($list_id,$newsletter_id,$name)
    {
        $this->worker     = \App::make('App\Droit\Newsletter\Worker\SubscriptionWorkerInterface');;
        $this->newsletter = \App::make('App\Droit\Newsletter\Repo\NewsletterInterface');
        $this->mailjet    = \App::make('App\Droit\Newsletter\Worker\MailjetServiceInterface');

        $this->newsletter_id = $newsletter_id;
        $this->list_id = $list_id;
        $this->name = $name;
    }

    /*
     * Get all already subscribed emails from Mailjet
     * Save them to an excel file
     * */
    public function save()
    {
        $emails = $this->getList();

        $data = collect($emails)->map(function ($item, $key) {return [$item];});

        \Excel::create($this->name, function($excel) use ($data) {
            $excel->sheet('Sheetname', function($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->store('xls', storage_path('excel'));

        return $this;
    }

    /*
     * Load all emails form excel file
     * */
    public function read()
    {
        $results = \Excel::load(storage_path('excel/'.$this->name.'.xls'), function($reader) {
            $reader->ignoreEmpty();
        })->get();

        return $results->flatten()->all();
    }

    /*
     * Get all already subscribed emails from Mailjet
     * */
    public function getList()
    {
        $this->mailjet->setList($this->list_id);

        foreach (range(0, 9000, 1000) as $i) {
            $users = $this->mailjet->getSubscribers($i);

            $allusers[] = collect($users)->map(function ($item, $key) {
                return $item['Email'];
            });
        }

        return \Arr::flatten($allusers);
    }

    /*
     * Get subcribers fromm DB active or not
     * */
    public function getSubscribers($active = false)
    {
        $pubdroit = $this->newsletter->find($this->newsletter_id);

        return $active ? $pubdroit->active_subscriptions->unique('email') : $pubdroit->subscriptions->unique('email');
    }

    /*
     * Filter emails unconfirmed (in DB and not active), missing (in DB and active but not on Mailjet), ok (in DB active and on Mailjet)
     * */
    public function filter()
    {
        $subscriptions = $this->getSubscribers();
        $emails        = $this->read();

        foreach ($subscriptions as $item){
            if(!in_array(strtolower($item->email),$emails)) {
                // if not in abos and confirmed => subscribe
                if($item->activated_at){
                    $this->subscribers['missing'][] = $item->email;
                }
                // if not in abos and not confirmed => delete
                if(!$item->activated_at){
                    $this->subscribers['unconfirmed'][] = $item->email;
                }
            }
            else{
                $this->subscribers['ok'][] = $item->email;
            }
        }

        return $this;
    }

    public function clean()
    {
        $subscriptions = $this->getSubscribers(true);
        $subscriptions = $subscriptions->map(function ($item) {
            $abos = $item->subscriptions->pluck('id')->all();

            $item->subscriptions()->detach();
            $item->subscriptions()->attach(array_unique($abos));
        });
        exit();
    }

    /*
     * Missing in DB but exist on Mailjet
     * */
    public function missingDB()
    {
        // Get all active subscribers in DB
        $subscriptions = $this->getSubscribers(true);
        $subscriptions = $subscriptions->map(function ($item) {
            return strtolower($item->email);
        })->unique()->all();

        // Get
        $emails      = $this->read();
        $subscribers = [];

        foreach ($emails as $email)
        {
            if(!in_array($email,$subscriptions)) {
                $subscribers['missing'][] = $email;
            }
            else{
                $subscribers['ok'][] = $email;
            }
        }

        return $subscribers;
    }

    /*
     * Subscribe on Mailjet emails missing
     * */
    public function setMissing()
    {
        if(!empty($this->subscribers['missing'])){
            $list = array_unique($this->subscribers['missing']);

            foreach ($list as $missing){
                $this->mailjet->subscribeEmailToList($missing);
            }
        }

        return $this;
    }

    /*
     * Add subscribers in DB
     * */
    public function addSubscriber($subscribers,$newsletter_id)
    {
        if(isset($subscribers['missing'])){
            foreach ($subscribers['missing'] as $email)
            {
                $subscriber = $this->worker->exist($email);

                if($subscriber){
                    $subscriber->subscriptions()->attach($newsletter_id);
                    $subscriber->activated_at = date('Y-m-d G:i:s');
                    $subscriber->save();
                }
                else{
                    $subscriber = $this->worker->activate($email,$newsletter_id);
                    $subscriber->activated_at = date('Y-m-d G:i:s');
                    $subscriber->save();
                }
            }
        }
    }

    /*
     * Delete unconfirmed in DB and not present on Mailjet
     * */
    public function deleteUnconfirmed()
    {
        if(!empty($this->subscribers['unconfirmed'])){
            foreach ($this->subscribers['unconfirmed'] as $missing){
                $subscriber = $this->worker->exist($missing);
                if($subscriber){
                    $subscriber->delete();
                }
            }
        }
    }
}