<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Champion;

class ChampionController extends AbstractController {

    function getChampionByKey($key) {
        // TODO -> busca key en el array de campeones de ENV -> CHAMPS
        $champion = new Champion();
        foreach ($_ENV['CHAMPS'] as $champ) {
            if ($champ->getChampKey() == $key) {
                $champion = $champ;
                break;
            }
        }
        return $this->render('champion/champData.html.twig',
                        ['champion' => $champion]);
    }

}
