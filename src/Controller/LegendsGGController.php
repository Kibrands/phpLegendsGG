<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class LegendsGGController extends AbstractController
{
    function index()
    {
        $result = new \stdClass();
        $result->API_KEY = $_ENV['API_KEY'];
        return $this->render('index.html.twig', [
            'active' => 'index'
        ]);
    }

    function tier()
    {
        return $this->render('tier.html.twig', [
            'active' => 'tier'
        ]);
    }

    function ranking()
    {
        return $this->render('ranking.html.twig', [
            'active' => 'ranking'
        ]);
    }

    function lolEsports()
    {
        return $this->render('lol-esports.html.twig', [
            'active' => 'lol-esports'
        ]);
    }

    function tftItems()
    {
        return $this->render('tft/tft-items.html.twig', [
            'active' => 'tft'
        ]);
    }

    function tftComps()
    {
        return $this->render('tft/tft-comps.html.twig', [
            'active' => 'tft'
        ]);
    }

    function tftRank()
    {
        return $this->render('tft/tft-rank.html.twig', [
            'active' => 'tft'
        ]);
    }

    function findSummoner(\Request $request)
    {
        $summoner = $request->request->get("summonerName");
        $server = $request->request->get("server");
        return $this->render();
    }

    function summoner($server, $summoner)
    {
        return $this->render('summoner.html.twig', [
            'active' => '',
            'server' => $server,
            'summoner' => $summoner,
            'key' => $_ENV['API_KEY']
        ]);
    }
}