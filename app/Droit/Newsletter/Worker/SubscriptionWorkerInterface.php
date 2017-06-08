<?php namespace App\Droit\Newsletter\Worker;

interface SubscriptionWorkerInterface{

    public function activate($email,$newsletter_id);
    public function subscribe($subscriber,$newsletter_ids);
    public function make($email,$newsletter_id);
    public function exist($email);
    public function unsubscribe($subscriber,$newsletter_ids);

}