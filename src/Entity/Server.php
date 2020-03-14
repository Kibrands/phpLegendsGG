<?php
namespace App\Entity;

class Server
{
    private $server;

    public function setServer($serverName)
    {
        switch ($serverName) {
            case 'na':
                $this->server = 'na1';
                break;
            case 'euw':
                $this->server = 'euw1';
                break;
            case 'eun':
                $this->server = 'eun1';
                break;
            case 'br':
                $this->server = 'br1';
                break;
            case 'lan':
                $this->server = 'la1';
                break;
            case 'las':
                $this->server = 'la2';
                break;
            case 'tr':
                $this->server = 'tr1';
                break;
            case 'ru':
                $this->server = 'ru';
                break;
            case 'oc':
                $this->server = 'oc1';
                break;
            case 'jp':
                $this->server = 'jp1';
                break;
            case 'kr':
                $this->server = 'kr';
                break;
            default:
                $this->server = 'err';
                break;
        }
    }

    public function getServer() : string
    {
        return $this->server;
    }
}