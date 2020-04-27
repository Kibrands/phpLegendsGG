<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Server;

class RankingController extends AbstractController {

    function renderRankingByServer($server) {
        // Creación objeto Server
        $serverObj = new Server();
        $serverObj->setServer($server);
        if ($serverObj->getServer() == 'err') { // Si no es válido, retorna error
            return 'err-server-not-valid';
        }
        // URLs API Y OPCIONES
        $urlBase = "https://" . $serverObj->getServer() . ".api.riotgames.com/lol/league/v4/challengerleagues/by-queue/";
        $urlSolo = $urlBase . 'RANKED_SOLO_5x5';
        $urlFlex = $urlBase . 'RANKED_FLEX_SR';
        $opciones = array('http' =>
            array(
                'method' => 'GET',
                'header' => 'X-Riot-Token: ' . $_ENV['API_KEY'],
                "ignore_errors" => true,
            )
        );
        $contexto = stream_context_create($opciones);
        // Coge datos de la API
        $resultadoSoloQ = file_get_contents($urlSolo, false, $contexto);
        $resultadoFlex = file_get_contents($urlFlex, false, $contexto);
        $jsonSolo = \json_decode($resultadoSoloQ);
        $jsonFlex = \json_decode($resultadoFlex);
        // Ordena de mayor a menor y coge los 3 primeros
        usort($jsonSolo->entries, function($a, $b) {
            if ($a->leaguePoints < $b->leaguePoints) {
                return 1;
            } elseif ($a->leaguePoints > $b->leaguePoints) {
                return -1;
            } else {
                return 0;
            }
        });
        if ($jsonSolo->entries > 3) {
            $jsonSolo->entries = array_slice($jsonSolo->entries, 0, 3);
        }
        usort($jsonFlex->entries, function($a, $b) {
            if ($a->leaguePoints < $b->leaguePoints) {
                return 1;
            } elseif ($a->leaguePoints > $b->leaguePoints) {
                return -1;
            } else {
                return 0;
            }
        });
        if ($jsonFlex->entries > 3) {
            $jsonFlex->entries = array_slice($jsonFlex->entries, 0, 3);
        }
        // Retorna render
        return $this->render('ranking/rankingData.html.twig',
                        ['rankingSolo' => $jsonSolo,
                            'rankingFlex' => $jsonFlex]);
    }

}
