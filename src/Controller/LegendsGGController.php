<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\RestController;
use Psr\Log\LoggerInterface;
use App\Entity\Summoner;

class LegendsGGController extends AbstractController
{
    function index()
    {
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

    function findSummoner(Request $request)
    {
        // Recogemos valores del formulario
        $summoner = $request->request->get("summonerName");
        $server = $request->request->get("server");
        return $this->redirectToRoute('summoner', [
            'server' => $server,
            'summoner' => $summoner
        ]);
    }

    function summoner($server, $summoner)
    {
        $restController = new RestController();
        // Los tratamos con restController->getSummoner()
        $summonerObj = $restController->getSummoner($server, $summoner);
        // Si hay algún error, lo controlamos
        if (is_string($summonerObj)) {
            if ($summonerObj == 'err-server-not-valid'
              || $summonerObj == 'err-summoner-not-found') {
              return $this->redirectToRoute('error', array(
                  'error' => $summonerObj
              ));
            }
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
            if (in_array($summonerObj->getSummonerLevel(), $value)) {
                $levelBorder = $key;
            } elseif ($summonerObj->getSummonerLevel() >= 300) {
                $levelBorder = "300";
            }
        }
        // Retorna al summoner
        return $this->render('summoner.html.twig', [
            'active' => '',
            'server' => $server,
            'summoner' => $summonerObj,
            'lol_patch' => $_ENV['LOL_PATCH'],
            'levelBorder' => $levelBorder
        ]);
    }

    function error($error)
    {
        // Error por defecto
        $errorResponse = "Aquí no hay nada";
        if ($error == 'err-server-not-valid') {
            $errorResponse = "Servidor no válido";
        }
        if ($error == 'err-summoner-not-found') {
            $errorResponse = 'El invocador no existe';
        }
        return $this->render('error.html.twig', array(
            'error' => $errorResponse,
            'active' => 'error'
        ));
    }

    function onlyError()
    {
        // Redirección para la página de error
        return $this->redirectToRoute('error', array(
            'error' => 'error'
        ));
    }
}