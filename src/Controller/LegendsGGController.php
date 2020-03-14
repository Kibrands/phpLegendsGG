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

    function findSummoner(Request $request, LoggerInterface $logger)
    {
        // Recogemos valores del formulario
        $restController = new RestController();
        $summoner = $request->request->get("summonerName");
        $server = $request->request->get("server");
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
        // Retorna al summoner
        $logger->info($summonerObj->getName());
        return $this->redirectToRoute('summoner', [
            'server' => $server,
            'summoner' => $summonerObj
        ]);
    }

    function summoner($server, $summoner)
    {
        return $this->render('summoner.html.twig', [
            'active' => '',
            'server' => $server,
            'summoner' => $summoner
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