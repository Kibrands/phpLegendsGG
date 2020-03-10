<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LegendsGGController extends AbstractController
{
    function index()
    {
        $result = new \stdClass();
        $result->API_KEY = $_ENV['API_KEY'];
        return new JsonResponse($result);
    }
}