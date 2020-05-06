<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use RiotAPI\LeagueAPI\LeagueAPI;

class LegendsGGController extends AbstractController {

    function index() {
        return $this->render('index.html.twig', [
                    'active' => 'index'
        ]);
    }

    function tier() {
        return $this->render('tier.html.twig', [
                    'active' => 'tier'
        ]);
    }

    function ranking(Request $request) {
        $server = $request->request->get('server');
        if ($server == null) {
            $server = 'na';
        }
        return $this->render('ranking.html.twig', [
                    'active' => 'ranking',
                    'server' => $server
        ]);
    }

    function lolEsports() {
        return $this->render('lol-esports.html.twig', [
                    'active' => 'lol-esports'
        ]);
    }

    function tftItems() {
        return $this->render('tft/tft-items.html.twig', [
                    'active' => 'tft'
        ]);
    }

    function tftComps() {
        $utilController = new UtilController();
        $comps = $utilController->getCompositions();
        return $this->render('tft/tft-comps.html.twig', [
                    'active' => 'tft',
                    'ddragon' => $_ENV['DDRAGON'],
                    'comps' => $comps
        ]);
    }

    function tftRank(Request $request) {
        $server = $request->request->get('server');
        if ($server == null) {
            $server = 'na';
        }
        return $this->render('tft/tft-rank.html.twig', [
                    'active' => 'tft',
                    'server' => $server
        ]);
    }

    function findSummoner(Request $request) {
        // Recogemos valores del formulario
        $summoner = $request->request->get("summonerName");
        $server = $request->request->get("server");
        return $this->redirectToRoute('summoner', [
                    'server' => $server,
                    'summoner' => $summoner
        ]);
    }

    function summoner($server, $summoner) {
        $utilController = new UtilController();
        $api = new LeagueAPI([
            LeagueAPI::SET_KEY => $_ENV['API_KEY'],
            LeagueAPI::SET_REGION => $utilController->getRegion($server),
        ]);
        $summoner = $api->getSummonerByName($summoner);
        // Borde
        $ranges = array(
            "1_29" => range(1, 29),
            "30_49" => range(30, 49),
            "50_74" => range(50, 74),
            "75_99" => range(75, 99),
            "100_124" => range(100, 124),
            "125_149" => range(125, 149),
            "150_174" => range(150, 174),
            "175_199" => range(175, 199),
            "200_224" => range(200, 224),
            "225_249" => range(225, 249),
            "250_299" => range(250, 299),
        );
        $levelBorder = "1_29";
        foreach ($ranges as $key => $value) {
            if (in_array($summoner->summonerLevel, $value)) {
                $levelBorder = $key;
            } elseif ($summoner->summonerLevel >= 300) {
                $levelBorder = "300";
            }
        }
        // Busca ligas
        $summonerLeagues = $api->getLeaguePositionsForSummoner($summoner->id);
        // Busca partidas
        $matches = $api->getMatchlistByAccount($summoner->accountId);
        if ($matches->totalGames > 20) {
            $matches->matches = array_slice($matches->matches, 0, 20);
        }
        // Retorna al summoner
        return $this->render('summoner.html.twig', [
                    'active' => '',
                    'server' => $server,
                    'summoner' => $summoner,
                    'lol_patch' => $_ENV['LOL_PATCH'],
                    'ddragon' => $_ENV['DDRAGON'],
                    'levelBorder' => $levelBorder,
                    'summonerLeagues' => $summonerLeagues,
                    'matches' => $matches,
                    'api' => $api
        ]);
    }

    function error($error) {
        // Error por defecto
        $errorResponse = "Aquí no hay nada";
        if ($error == 'err-server-not-valid') {
            $errorResponse = "Servidor no válido";
        }
        if ($error == 'err-summoner-not-found') {
            $errorResponse = 'El invocador no existe';
        }
        if ($error == 'err-api-key') {
            $errorResponse = 'La Api Key ha caducado, por favor, contacte con el dueño de la página';
        }
        return $this->render('error.html.twig', array(
                    'error' => $errorResponse,
                    'active' => 'error'
        ));
    }

    function onlyError() {
        // Redirección para la página de error
        return $this->redirectToRoute('error', array(
                    'error' => 'error'
        ));
    }

}
