<?php namespace App\Droit\Newsletter\Worker;

interface MailjetInterface {

    /**
     * Subscriptions
     */
    public function getSubscribers();
    public function getAllSubscribers();
    public function addContact($email);
    public function getContactByEmail($contactEmail);
    public function addContactToList($contactID);
    public function subscribeEmailToList($email);
    public function removeContact($email);

    /**
     * Lists
     */
    public function getListRecipient($email);

    /**
     * Campagnes
     */
    public function getCampagne($CampaignID);
    public function createCampagne($campagne);
    public function setHtml($html,$id);
    public function sendTest($email,$html,$sujet);
    public function sendCampagne($id,$CampaignID);

    /**
     * Statistiques
     */
    public function statsCampagne($id);
    public function statsListe();
    public function campagneAggregate($id);

}
