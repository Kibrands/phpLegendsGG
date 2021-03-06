<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use RiotAPI\LeagueAPI\LeagueAPI;
use Symfony\Component\Finder\Finder;
use RiotAPI\LeagueAPI\Exceptions\ForbiddenException;
use RiotAPI\LeagueAPI\Exceptions\DataNotFoundException;

/**
* LegendsGGController
*
* Controlador principal de la aplicación web Legends GG.
*
* @author Julio de la Matta Cadenas <juliomattacadenas@gmail.com>
* @copyright 2020 Julio de la Matta Cadenas
* @license http://www.fsf.org/licensing/licenses/gpl.txt GPL 2 or later
* @version 1.1.0
* @link https://github.com/Kibrands/phpLegendsGG
*/
class LegendsGGController extends AbstractController {

    function index() {
        return $this->render('index.html.twig', [
                    'active' => 'index'
        ]);
    }

    function versus() {
        return $this->render('versus.html.twig', [
                    'active' => 'versus'
        ]);
    }

    function versusFind(Request $request) {
        // Recogemos valores del formulario
        $summoner1 = $request->request->get("summoner1");
        $summoner2 = $request->request->get("summoner2");
        $server = $request->request->get("server");
        return $this->redirectToRoute('versus-action', [
                    'server' => $server,
                    'summoner1' => $summoner1,
                    'summoner2' => $summoner2
        ]);
    }

    function versusAction($server, $summoner1, $summoner2) {
        $utilController = new UtilController();
        $api = new LeagueAPI([
            LeagueAPI::SET_KEY => $_ENV['API_KEY'],
            LeagueAPI::SET_REGION => $utilController->getRegion($server),
        ]);
        try {
            // Summoners
            $summonerObj1 = $api->getSummonerByName($summoner1);
            $summonerObj2 = $api->getSummonerByName($summoner2);
            // Leagues
            $summoner1Leagues = $api->getLeaguePositionsForSummoner($summonerObj1->id);
            $summoner2Leagues = $api->getLeaguePositionsForSummoner($summonerObj2->id);
        } catch (ForbiddenException | DataNotFoundException $ex) {
            return $this->redirectToRoute('error', [
               'error' => $ex->getCode()
            ]);
        }

        return $this->render('versus/versusAction.html.twig', [
                    'active' => 'versus',
                    'server' => $server,
                    'summoner1' => $summonerObj1,
                    'summoner2' => $summonerObj2,
                    'summoner1Leagues' => $summoner1Leagues,
                    'summoner2Leagues' => $summoner2Leagues,
                    'lol_patch' => $_ENV['LOL_PATCH'],
                    'ddragon' => $_ENV['DDRAGON']
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
        $finder = new Finder();
        $finder->files()->in(__DIR__)->name('lolesports-news.json');

        foreach ($finder as $file) {
            $news = json_decode(file_get_contents($file));
        }

        return $this->render('lol-esports.html.twig', [
                    'active' => 'lol-esports',
                    'news' => $news
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
        try {
            $summonerObj = $api->getSummonerByName($summoner);
        } catch (ForbiddenException | DataNotFoundException $ex) {
            return $this->redirectToRoute('error', [
               'error' => $ex->getCode()
            ]);
        }
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
            if (in_array($summonerObj->summonerLevel, $value)) {
                $levelBorder = $key;
            } elseif ($summonerObj->summonerLevel >= 300) {
                $levelBorder = "300";
            }
        }
        // Busca ligas
        $summonerLeagues = $api->getLeaguePositionsForSummoner($summonerObj->id);
        // Busca partidas
        $matches = $api->getMatchlistByAccount($summonerObj->accountId);
        if ($matches->totalGames > 20) {
            $matches->matches = array_slice($matches->matches, 0, 20);
        }
        // Retorna al summoner
        return $this->render('summoner.html.twig', [
                    'active' => '',
                    'server' => $server,
                    'summoner' => $summonerObj,
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
        $errorResponse = $error;
        if ($error == '404') {
            $errorResponse = 'No hemos encontrado lo que estabas buscando...';
        }
        if ($error == '403') {
            $errorResponse = 'La Api Key ha caducado, por favor, contacte con el dueño de la página.';
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
