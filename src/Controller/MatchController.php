<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\DataDragonAPI\DataDragonAPI;

class MatchController extends AbstractController {

    function getMatchData($server, $matchId, $accountId) {
        $utilController = new UtilController();
        $api = new LeagueAPI([
            LeagueAPI::SET_KEY => $_ENV['API_KEY'],
            LeagueAPI::SET_REGION => $utilController->getRegion($server),
        ]);
        $matchData = $api->getMatch($matchId);
        return $this->render('match/matchData.html.twig',
                        ['matchData' => $matchData,
                            'accountId' => $accountId,
                            'server' => $server,
                            'ddragon' => $_ENV['DDRAGON'],
                            'lol_patch' => $_ENV['LOL_PATCH']]);
    }

    function getSpellImage($server, $spellId) {
        $utilController = new UtilController();
        $api = new LeagueAPI([
            LeagueAPI::SET_KEY => $_ENV['API_KEY'],
            LeagueAPI::SET_REGION => $utilController->getRegion($server),
        ]);
        DataDragonAPI::initByCdn();
        $spell = $api->getStaticSummonerSpell($spellId);
        return $this->render('spell/spellData.html.twig',
                        ['spell' => $spell]);
    }

}
