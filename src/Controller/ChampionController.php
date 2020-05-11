<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use RiotAPI\DataDragonAPI\DataDragonAPI;
use RiotAPI\LeagueAPI\LeagueAPI;

class ChampionController extends AbstractController {

    function getChampionByKey($server ,$key) {
        $utilController = new UtilController();
        $api = new LeagueAPI([
            LeagueAPI::SET_KEY => $_ENV['API_KEY'],
            LeagueAPI::SET_REGION => $utilController->getRegion($server),
        ]);
        DataDragonAPI::initByCdn();
        $champion = $api->getStaticChampion($key);
        return $this->render('champion/champData.html.twig',
                        ['champion' => $champion]);
    }

}
