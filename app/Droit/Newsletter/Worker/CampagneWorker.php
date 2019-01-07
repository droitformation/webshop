<?php namespace App\Droit\Newsletter\Worker;

use App\Droit\Newsletter\Repo\NewsletterContentInterface;
use App\Droit\Newsletter\Repo\NewsletterCampagneInterface;
use App\Droit\Newsletter\Repo\NewsletterInterface;
use App\Droit\Newsletter\Repo\NewsletterUserInterface;
use \InlineStyle\InlineStyle;

class CampagneWorker implements CampagneInterface{

    protected $content;
    protected $campagne;
    protected $newsletter;
    protected $subscription;

	public function __construct(NewsletterContentInterface $content, NewsletterCampagneInterface $campagne, NewsletterInterface $newsletter, NewsletterUserInterface $subscription)
	{
        $this->content      = $content;
        $this->campagne     = $campagne;
        $this->newsletter   = $newsletter;
        $this->subscription = $subscription;
	}

    /**
     * Interaction with site controllers
     * List of arrets in non sent campagne to hide on frontend
     */
    public function arretsToHide($newsletter_id = null)
    {
        $campagnes = $this->campagne->getAll($newsletter_id)->where('status','brouillon');

        return $campagnes->flatMap(function ($campagne) {
                return $campagne->content;
            })->map(function ($content, $key) {

                if($content->arret_id)
                    return $content->arret_id ;

                if($content->groupe_id > 0)
                    return $content->groupe->arrets->pluck('id')->all();
            
            })->filter(function ($value, $key) {
                return !empty($value);
            })->flatten()->toArray();
    }

    /**
     * Interaction with site controllers
     * List of arrets in non sent campagne to hide on frontend
     */
    public function excludeArrets($site_id)
    {
        return $this->campagne->getAllBySite($site_id)->flatMap(function ($campagne) {
            return $campagne->content;
        })->map(function ($content, $key) {

            if($content->arret_id)
                return $content->arret_id ;

            if($content->groupe_id > 0)
                return $content->groupe->arrets->pluck('id')->all();

        })->filter(function ($value, $key) {
            return !empty($value);
        })->flatten()->toArray();
    }

    /**
     * Get campagne for site
     */
    public function getCampagne($id, $archive = null)
    {
        if($archive){
            return $this->campagne->old($id);
        }

        return $this->campagne->find($id);
    }

    /**
     * Last sent campagne for newsletter
     */
    public function last($newsletter_id = null)
    {
        return $this->campagne->getLastCampagne($newsletter_id);
    }

    /**
     * Last sent campagne for site
     */
    public function lastBySite($site_id)
    {
        return $this->campagne->getLastCampagneBySite($site_id);
    }

    /**
     * Archives by year
     */
    public function getArchives($newsletter_id,$year)
    {
        return $this->campagne->getArchives($newsletter_id,$year);
    }

    /**
     * Archives by year
     */
    public function getArchivesBySite($site_id,$year)
    {
        return $this->campagne->getArchivesBySite($site_id,$year);
    }

    /**
     * All newsletter for site
     */
    public function siteNewsletters($site_id)
    {
        if(config('newsletter.multi'))
        {
            return $this->newsletter->getSite($site_id);
        }

        return null;
    }

    /**
     * All campagnes for site
     */
    public function siteCampagnes($site_id)
    {
        $newsletters = $this->newsletter->getSite($site_id);
        $campagnes = $newsletters->map(function ($newsletter, $key) {
            return $newsletter->campagnes_visibles;
        })->flatten(1);

        return $campagnes;
    }
    
    /**
     * Get subscriptions for given email
     * @param $email
     * @return Newsletter_users model
     */
    public function hasSubscriptions($email)
    {
        return $this->subscription->findByEmail($email);
    }
    
    /**
     * Put styles inline for campagne
     * Used when sending cammpagne or test 
     * */
    public function html($id)
    {
        $context = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];
        
        libxml_use_internal_errors(true);
        $htmldoc = new InlineStyle( file_get_contents( url('campagne/'.$id), false, stream_context_create($context)));

        $htmldoc->applyStylesheet($htmldoc->extractStylesheets());

        $html = $htmldoc->getHTML();
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);

        return $html;
    }
}
