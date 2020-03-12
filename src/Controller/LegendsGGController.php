<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LegendsGGController extends AbstractController
{
    function index()
    {
        $result = new \stdClass();
        $result->API_KEY = $_ENV['API_KEY'];
        $active = true;
        return $this->render('base.html.twig', [
            'active' => $active
        ]);
    }

    function tier(){}
    function ranking(){}
    function lolEsports(){}
}