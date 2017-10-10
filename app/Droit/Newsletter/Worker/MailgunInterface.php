<?php
namespace App\Droit\Newsletter\Worker;

interface MailgunInterface
{
    public function setSender($email,$name = null);
    public function setSendDate($date);
    public function setRecipients($emails);
    public function setHtml($html);
    public function getHtml();
    public function getSender();
    public function getRecipients();

/*    public function getList();
    public function addList($address, $name, $description);
    public function getAllLists();
    public function getSubscribers();
    public function getAllSubscribers();

    public function addContact($email);
    public function getContactByEmail($contactEmail);
    public function addContactToList($contactID);
    public function subscribeEmailToList($email);
    public function removeContact($email);
    public function getListRecipient($email);


    public function getAllCampagne();
    public function getCampagne($CampaignID);
    public function updateCampagne($CampaignID, $status);
    public function createCampagne($campagne);
    public function deleteCampagne($id);
*/

    public function prepareEmail($sujet,$id = null);
    public function prepareRecipients();

    public function sendTransactional($sujet);
    public function sendCampagne($campagne);
    public function sendBulk($campagne,$timestamp);
    public function send($data);

    /**
     * Statistiques
     */
    //public function statsCampagne($id);
    //public function clickStatistics($id, $offset = 0);

    /*
     * Misc test
     * */
    public function hasList();
    public function hasHtml();
    public function hasRecipients();

}