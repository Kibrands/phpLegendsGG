<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Champion;

class ChampionController extends AbstractController {

    function getChampionByKey($key) {
        $entityManager = $this->getDoctrine()->getManager();
        $champion = $entityManager->getRepository(Champion::class)->findOneByChampKey($key);
        if ($champion == null) {
            $champion = new Champion();
            $champion->setName('err-champ-not-found');
        }
        return $this->render('champion/champData.html.twig',
                        ['champion' => $champion]);
    }

}
