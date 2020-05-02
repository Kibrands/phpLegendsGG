<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use RiotAPI\DataDragonAPI\DataDragonAPI;

class ChampionController extends AbstractController {

    function getChampionByKey($api ,$key) {
        DataDragonAPI::initByCdn();
        $champion = $api->getStaticChampion($key);
        return $this->render('champion/champData.html.twig',
                        ['champion' => $champion]);
    }

}
