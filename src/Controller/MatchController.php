<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use RiotAPI\LeagueAPI\LeagueAPI;

class MatchController extends AbstractController {

    function getMatchData($server, $matchId) {
        $utilController = new UtilController();
        $api = new LeagueAPI([
            LeagueAPI::SET_KEY => $_ENV['API_KEY'],
            LeagueAPI::SET_REGION => $utilController->getRegion($server),
        ]);
        $matchData = $api->getMatch($matchId);
        return $this->render('match/matchData.html.twig',
                        ['matchData' => $matchData]);
    }

}
