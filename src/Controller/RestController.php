<?php
namespace App\Controller;

use App\Entity\Server;
use App\Entity\Summoner;

class RestController
{
    function getSummoner($server, $summonerName)
    {
        // Creación objeto Server
        $serverObj = new Server();
        $serverObj->setServer($server);
        if ($serverObj->getServer() == 'err') { // Si no es válido, retorna error
            return 'err-server-not-valid';
        }
        // URL API Y OPCIONES
        $url = "https://" . $serverObj->getServer() . ".api.riotgames.com/lol/summoner/v4/summoners/by-name/" . \str_replace(" ", "%20", $summonerName);
        $opciones = array('http' =>
            array(
                'method'  => 'GET',
                'header'  => 'X-Riot-Token: ' . $_ENV['API_KEY'],
                "ignore_errors" => true,
            )
        );
        $contexto = stream_context_create($opciones);
        // Coge datos de la API
        $resultado = file_get_contents($url, false, $contexto);
        $json = \json_decode($resultado);
        if (isset($json->status)) { // Si el código es 404, retorna error
            if ($json->status->status_code == 404) {
                return 'err-summoner-not-found';
            }
        }
        // Creación objeto Summoner
        $summoner = new Summoner();
        $summoner->setProfileIconId($json->profileIconId);
        $summoner->setName($json->name);
        $summoner->setPuuid($json->puuid);
        $summoner->setSummonerLevel($json->summonerLevel);
        $summoner->setAccountId($json->accountId);
        $summoner->setId($json->id);
        $summoner->setRevisionDate($json->revisionDate);
        // Retornamos summoner
        return $summoner;
    }
}