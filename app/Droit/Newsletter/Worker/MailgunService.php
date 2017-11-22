<?php namespace App\Droit\Newsletter\Worker;

use App\Droit\Newsletter\Worker\MailgunInterface;

use Illuminate\Http\Request;
use \SendGrid;

use Mailgun\Mailgun;

class MailgunService implements MailgunInterface
{
    protected $mailgun;

    // You have to set
    protected $sender = '';
    protected $html   = null;
    protected $recipients = [];
    protected $sendDate = null;
    protected $tags = ['transactionnal'];

    //protected $list   = null;

    public $isTest = false;

    public function __construct(Mailgun $mailgun)
    {
        $this->mailgun = $mailgun;
    }

    /*
     * Formats:
     *
     * DroitNE <info@droitne.ch>
     * info@droitne.ch
     *
     * */
    public function setSender($email,$name = null)
    {
        $this->sender = $name ? $name.' <'.$email.'>' : $email;

        return $this;
    }

    /*
     * toRfc2822String with carbon
     * */
    public function setSendDate($date)
    {
        $this->sendDate = $date;

        return $this;
    }

    public function setRecipients($emails)
    {
        $this->recipients = $emails;

        return $this;
    }

    public function setHtml($html)
    {
        $this->html = $html;

        return $this;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function getRecipients()
    {
        return $this->recipients;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function prepareEmail($sujet,$id = null)
    {
        $data = [
            'from'                => $this->sender,
            "subject"             => $sujet,
            'to'                  => $this->recipients,
            "html"                => $this->html,
            "text"                => strip_tags($this->html,'<a>'),
            "recipient-variables" => json_encode($this->prepareRecipients()), // Required for batch sending, matches to recipient details
            "v:messageId"         => $id ? 'message_'.$id : null, // Custom variable used for webhooks
            'o:deliverytime'      => $this->sendDate ? $this->sendDate : null,
            'o:tag'               => $id ? ['message_'.$id] : $this->tags
        ];

        return array_filter($data);
    }

    public function prepareRecipients()
    {
        return collect($this->recipients)->mapWithKeys(function ($email, $key) {
            return [$email => ['id' => $key + 1]];
        })->toArray();
    }

    public function sendCampagne($campagne)
    {
        $sujet = $this->isTest ? 'TEST | '.$campagne->sujet : $campagne->sujet;

        $data = $this->prepareEmail($sujet,$campagne->id);

        return $this->send($data);
    }

    public function sendTransactional($sujet)
    {
        $data = $this->prepareEmail($sujet);

        return $this->send($data);
    }

    public function send($data)
    {
        $this->hasHtml()->hasRecipients();

        $response = $this->mailgun->sendMessage(config('mailgun.domain'), $data);

        if($response->http_response_code == 200){
            // local env is configured with pastebin no id returned, faking it
            return isset($response->http_response_body->id) ? $response->http_response_body->id : 1982;
        }

        throw new \App\Exceptions\NewsletterImplementationException($response->http_response_body, $response->http_response_code);
    }

    /*
     * Send transactional
     * Mouais
     * */
    public function sendBulk($campagne ,$timestamp)
    {
        $batchMsg = $this->mailgun->BatchMessage('mg.droitne.ch');

        # Define the from address.
        $batchMsg->setFromAddress("info@droit.ch", ['name' => 'DroitNe']);
        # Define the subject.
        $batchMsg->setSubject($campagne->sujet);
        # Define the body of the message.
        $batchMsg->setHtmlBody($this->html);
        $batchMsg->setTextBody(strip_tags($this->html,'<a>'));
        $batchMsg->setDeliveryTime($timestamp,'Europe/Zurich');
        $batchMsg->setClickTracking(true);
        $batchMsg->addCustomHeader("campagne_id", $campagne->id);

        foreach ($this->recipients as $key => $recipient){
            $batchMsg->addToRecipient($recipient, ["id" => $key + 1]);
        }

        $batchMsg->finalize();
    }

    public function getStats($date,$tag){

        $yesterday  = \Carbon\Carbon::parse($date)->startOfDay()->timestamp;
        $today      = \Carbon\Carbon::parse($date)->endOfDay()->timestamp;

        $data = [
            'event' => ['accepted', 'delivered', 'failed'],
            'start' => $yesterday,
            'end'   => $today,
        ];

        $domain   = env('MAILGUN_DOMAIN');
        $response = $this->mailgun->get($domain.'/tags/'.$tag.'/stats',$data);
        //$response = $this->mailgun->get($domain.'/tags');

        if($response->http_response_code == 200){
            return $response->http_response_body;
        }

        throw new \App\Exceptions\NewsletterImplementationException($response->http_response_body, $response->http_response_code);

    }

    /*
     * Misc test
     * */
    public function hasList()
    {
        if(!$this->list){
            throw new \App\Exceptions\ListNotSetException('Attention aucune liste indiqué');
        }

        return $this;
    }

    public function hasHtml()
    {
        if(!$this->html){
            throw new \App\Exceptions\ListNotSetException('Attention aucun contenu indiqué');
        }

        return $this;
    }

    public function hasRecipients()
    {
        if(empty($this->recipients)){
            throw new \App\Exceptions\ListNotSetException('Attention aucun recipient indiqué');
        }

        return $this;
    }

    public function mailgun_agregate($data)
    {
        if(isset($data->stats)){
            $agg = collect($data->stats)->map(function ($item, $key) {
                return [
                    'accepted'  => $item->accepted->total,
                    'delivered' => $item->delivered->total
                ];
            });

            return [
                'accepted'  => $agg->pluck('accepted')->sum(),
                'delivered' => $agg->pluck('delivered')->sum()
            ];
        }

        return null;
    }
}