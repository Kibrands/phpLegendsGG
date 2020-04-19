<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Server;
use App\Entity\Summoner;

class MatchController extends AbstractController {

    function getMatchData($server, $matchId) {
        // Creación objeto Server
        $serverObj = new Server();
        $serverObj->setServer($server);
        if ($serverObj->getServer() == 'err') { // Si no es válido, retorna error
            return 'err-server-not-valid';
        }
        // URL API Y OPCIONES
        $url = "https://" . $serverObj->getServer() . ".api.riotgames.com/lol/match/v4/matches/" . $matchId;
        $opciones = array('http' =>
            array(
                'method' => 'GET',
                'header' => 'X-Riot-Token: ' . $_ENV['API_KEY'],
                "ignore_errors" => true,
            )
        );
        $contexto = stream_context_create($opciones);
        // Coge datos de la API
        $resultado = file_get_contents($url, false, $contexto);
        $json = \json_decode($resultado);
        if (isset($json->status)) { // Si el código es 404, retorna error
            if ($json->status->status_code == 404) {
                return 'err-match-not-found';
            } elseif ($json->status->status_code == 403) {
                return 'err-api-key';
            }
        }
        return $this->render('match/matchData.html.twig',
                        ['matchData' => $json]);
    }

}
