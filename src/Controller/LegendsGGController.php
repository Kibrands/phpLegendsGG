<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LegendsGGController extends AbstractController
{
    function index()
    {
        $result = new \stdClass();
        $result->API_KEY = $_ENV['API_KEY'];
        return $this->render('base.html.twig', [
            'result' => $result
        ]);
    }
}