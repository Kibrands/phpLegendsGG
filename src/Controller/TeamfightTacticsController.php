<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use RiotAPI\LeagueAPI\LeagueAPI;

class TeamfightTacticsController extends AbstractController {

    function renderRankingByServer($server) {
        $utilController = new UtilController();
        $api = new LeagueAPI([
            LeagueAPI::SET_KEY => $_ENV['API_KEY'],
            LeagueAPI::SET_REGION => $utilController->getRegion($server),
        ]);
        $tftLeague = $api->getTFTChallengerLeague();
        // Ordena de mayor a menor y coge los 3 primeros
        usort($tftLeague->entries, function($a, $b) {
            if ($a->leaguePoints < $b->leaguePoints) {
                return 1;
            } elseif ($a->leaguePoints > $b->leaguePoints) {
                return -1;
            } else {
                return 0;
            }
        });
        if ($tftLeague->entries > 3) {
            $tftLeague->entries = array_slice($tftLeague->entries, 0, 3);
        }
        // Retorna render
        return $this->render('tft/tft-rankData.html.twig',
                        ['ranking' => $tftLeague]);
    }
    
}