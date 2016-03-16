<?php

namespace App\Droit\Newsletter\Worker;

use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Newsletter\Worker\MailjetInterface;
use App\Droit\Newsletter\Repo\NewsletterUserInterface;
use Maatwebsite\Excel\Excel;

class ImportWorker implements ImportWorkerInterface
{
    protected $mailjet;
    protected $subscriber;
    protected $newsletter;
    protected $excel;

    public function __construct(MailjetInterface $mailjet , NewsletterUserInterface $subscriber, NewsletterInterface $newsletter, Excel $excel)
    {
        $this->mailjet    = $mailjet;
        $this->newsletter = $newsletter;
        $this->subscriber = $subscriber;
        $this->excel      = $excel;
    }

    public function subscribe($results,$list = null)
    {
        foreach($results as $email)
        {
            $subscriber = $this->subscriber->findByEmail($email->email);

            if(!$subscriber)
            {
                $subscriber = $this->subscriber->create([
                    'email'         => $email->email,
                    'activated_at'  => \Carbon\Carbon::now(),
                    'newsletter_id' => $list
                ]);
            }

            $this->subscriber->subscribe($subscriber->id,$list);
        }
    }

    public function read($file)
    {
        return $this->excel->load($file, function($reader) {
            $reader->ignoreEmpty();
            $reader->setSeparator('\r\n');
        })->get();
    }

    public function store($file)
    {
        // Convert to csv
        $this->excel->load($file)->store('csv', public_path('files/import'));
    }

    public function sync($file,$list)
    {
        // Import csv to mailjet
        $newsletter = $this->newsletter->find($list);
        $this->mailjet->setList($newsletter->list_id); // testing list

        $filename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file['name']);

        $dataID   = $this->mailjet->uploadCSVContactslistData(file_get_contents(public_path('files/import/'.$filename.'.csv')));
        $response = $this->mailjet->importCSVContactslistData($dataID->ID);
    }
}