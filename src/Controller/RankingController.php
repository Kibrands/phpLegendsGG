<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use RiotAPI\LeagueAPI\LeagueAPI;

class RankingController extends AbstractController {

    function renderRankingByServer($server) {
        $utilController = new UtilController();
        $api = new LeagueAPI([
            LeagueAPI::SET_KEY => $_ENV['API_KEY'],
            LeagueAPI::SET_REGION => $utilController->getRegion($server),
        ]);
        $soloQ = $api->getLeagueChallenger('RANKED_SOLO_5x5');
        $flex = $api->getLeagueChallenger('RANKED_FLEX_SR');
        // Ordena de mayor a menor y coge los 3 primeros
        usort($soloQ->entries, function($a, $b) {
            if ($a->leaguePoints < $b->leaguePoints) {
                return 1;
            } elseif ($a->leaguePoints > $b->leaguePoints) {
                return -1;
            } else {
                return 0;
            }
        });
        if ($soloQ->entries > 3) {
            $soloQ->entries = array_slice($soloQ->entries, 0, 3);
        }
        usort($flex->entries, function($a, $b) {
            if ($a->leaguePoints < $b->leaguePoints) {
                return 1;
            } elseif ($a->leaguePoints > $b->leaguePoints) {
                return -1;
            } else {
                return 0;
            }
        });
        if ($flex->entries > 3) {
            $flex->entries = array_slice($flex->entries, 0, 3);
        }
        // Retorna render
        return $this->render('ranking/rankingData.html.twig',
                        ['rankingSolo' => $soloQ,
                            'rankingFlex' => $flex]);
    }

}
