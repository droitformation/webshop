<?php

namespace App\Droit\Newsletter\Worker;

use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Newsletter\Worker\MailjetServiceInterface;
use App\Droit\Newsletter\Repo\NewsletterUserInterface;
use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use App\Droit\Newsletter\Worker\CampagneInterface;
use App\Droit\Service\UploadInterface;
use Maatwebsite\Excel\Excel;

use App\Jobs\SendBulkEmail;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\UploadedFile;

class ImportWorker implements ImportWorkerInterface
{
    use DispatchesJobs;

    protected $mailjet;
    protected $subscriber;
    protected $newsletter;
    protected $excel;
    protected $campagne;
    protected $worker;
    protected $upload;

    protected $file = null;

    public function __construct(
        MailjetServiceInterface $mailjet ,
        NewsletterUserInterface $subscriber,
        NewsletterInterface $newsletter,
        Excel $excel,
        NewsletterCampagneInterface $campagne,
        CampagneInterface $worker,
        UploadInterface $upload
    )
    {
        $this->mailjet    = $mailjet;
        $this->newsletter = $newsletter;
        $this->subscriber = $subscriber;
        $this->excel      = $excel;
        $this->campagne   = $campagne;
        $this->worker     = $worker;
        $this->mailjet    = $mailjet;
        $this->upload     = $upload;
    }

    public function setFile(UploadedFile $file)
    {
        $this->file = $file;

        return $this;
    }

    /*
     * @param  UploadedFile $file
     * @return Array of emails
     * */
    public function uploadAndRead()
    {
        $path = \Storage::disk('imports')->put('', $this->file);

        if(!$path) {
            throw new \App\Exceptions\FileUploadException('Upload failed');
        }

        // Read uploded xls
        $results = $this->read(public_path('files/imports/'.$path));

        // filter empty value and non email
        return validateListEmail($results);
    }

    public function import($data, UploadedFile $file)
    {
    /*   $newsletter_id = isset($data['newsletter_id']) && $data['newsletter_id'] > 0 ? $data['newsletter_id'] : null;
        $file = $this->upload->upload( $file , 'files/import' );
        if(!$file) { throw new \App\Exceptions\FileUploadException('Upload failed');}
        $path = public_path('files/import/'.$file['name']);  */
        // Read uploaded xls
        //$results = $this->read($path);
        //$path = \Storage::disk('imports')->put('', $file);
        //if(!$path) {throw new \App\Exceptions\FileUploadException('Upload failed');}
        // Read uploded xls
        //$results = $this->import->read(public_path('files/imports/'.$path));
        //$emails = validateListEmail($results);

        $newsletter_id = isset($data['newsletter_id']) && $data['newsletter_id'] > 0 ? $data['newsletter_id'] : null;

        $emails = $this->setFile($file)->uploadAndRead();

        // we want to import in one of the newsletter subscriber's list
        if($newsletter_id) {
            // Subscribe the new emails
            $this->subscribe($emails->flatten(),$newsletter_id);

            // Store imported file as csv for mailjet sync
            $name = $this->storeToCsv($emails->flatten()->all());

            // Mailjet sync
            $this->sync($name, $newsletter_id);
        }

        return $emails->flatten()->all();
    }

    public function subscribe($results,$list = null)
    {
        foreach($results as $email) {

            $subscriber = $this->subscriber->findByEmail($email);

            if(!$subscriber) {
                $subscriber = $this->subscriber->create([
                    'email'         => $email,
                    'activated_at'  => \Carbon\Carbon::now(),
                    'newsletter_id' => $list
                ]);
            }

            $this->subscriber->subscribe($subscriber->id,$list);
        }
    }

    /*
     * @return Array multidimensionnal
     * */
    public function read($file)
    {
        $results = \Excel::toArray(new \App\Imports\EmailImport, $file);

        if(!isset($results) || empty(\Arr::flatten($results))) {
            throw new \App\Exceptions\BadFormatException('Le fichier est vide ou mal formaté');
        }

        return $results;
    }

    /*
     * Convert to csv
     * */
    public function storeToCsv(array $data)
    {
        $image_name = 'conversion_'.rand(2000,6000);

        if($this->file){
            $name = $this->file->getClientOriginalName();
            $ext  = $this->file->getClientOriginalExtension();

            $image_name = basename($name,'.'.$ext);
        }

        $fp = fopen(public_path('files/imports/'.$image_name.'.csv'), 'wb');

        fputcsv($fp, $data);
       // foreach ($data as $fields) {fputcsv($fp, $fields);}

        fclose($fp);

        return $image_name;
    }

    public function sync($file,$list)
    {
        // Import csv to mailjet
        $newsletter = $this->newsletter->find($list);
        $this->mailjet->setList($newsletter->list_id); // testing list

        $filename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);

        $dataID   = $this->mailjet->uploadCSVContactslistData(file_get_contents(public_path('files/import/'.$filename.'.csv')));
        $response = $this->mailjet->importCSVContactslistData($dataID);
    }

    public function send($campagne_id,$list)
    {
        $campagne = $this->campagne->find($campagne_id);
        $html     = $this->worker->html($campagne_id);
        
        if(!$list->emails->isEmpty() && $campagne && $html)
        {
            $recipients = $list->emails->unique()->reject(function ($email, $key) {
                return !filter_var($email->email, FILTER_VALIDATE_EMAIL) || empty($email->email);
            })->pluck('email');

            // Send only 200 at the time to avoid timeout
            // dispatch to jobs
            $chunks = $recipients->chunk(200);

            $min = 1;
            foreach ($chunks as $chunk){
                $job = (new SendBulkEmail($campagne,$html,$chunk->toArray()))->delay(\Carbon\Carbon::now()->addMinutes($min));
                $this->dispatch($job);
                $min += 1;
            }

            return true;
        }

        return true;
    }
}