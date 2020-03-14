<?php
namespace App\Entity;

class Summoner
{
    private $profileIconId;
    private $name;
    private $puuid;
    private $summonerLevel;
    private $accountId;
    private $id;
    private $revisionDate;

    /*
        GETTERS AND SETTERS
    */

    public function setProfileIconId($profileIconId)
    {
        $this->profileIconId = $profileIconId;
    }

    public function getProfileIconId() : int
    {
        return $this->profileIconId;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setPuuid($puuid)
    {
        $this->puuid = $puuid;
    }

    public function getPuuid() : string
    {
        return $this->puuid;
    }

    public function setSummonerLevel($summonerLevel)
    {
        $this->summonerLevel = $summonerLevel;
    }

    public function getSummonerLevel() : int
    {
        return $this->summonerLevel;
    }

    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }
    
    public function getAccountId() : string
    {
        return $this->accountId;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getId() : string
    {
        return $this->id;
    }

    public function setRevisionDate($revisionDate)
    {
        $this->revisionDate = date('m/d/Y H:i:s', $revisionDate);
    }
    
    public function getRevisionDate() : date
    {
        return $this->revisionDate;
    }
}