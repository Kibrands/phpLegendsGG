<?php

namespace App\Controller;

use RiotAPI\LeagueAPI\Definitions\Region;
use Symfony\Component\Finder\Finder;

class UtilController {

    function getRegion($server) {
        switch ($server) {
            case 'na':
                return Region::NORTH_AMERICA;
            case 'euw':
                return Region::EUROPE_WEST;
            case 'eun':
                return Region::EUROPE_EAST;
            case 'br':
                return Region::BRASIL;
            case 'lan':
                return Region::LAMERICA_NORTH;
            case 'las':
                return Region::LAMERICA_SOUTH;
            case 'tr':
                return Region::TURKEY;
            case 'ru':
                return Region::RUSSIA;
            case 'oc':
                return Region::OCEANIA;
            case 'jp':
                return Region::JAPAN;
            case 'kr':
                return Region::KOREA;
            default:
                return Region::NORTH_AMERICA;
        }
    }

    function getCompositions() {
        $finder = new Finder();
        $finder->files()->in(__DIR__)->name('tft-comps.json');

        foreach ($finder as $file) {
            $comps = json_decode(file_get_contents($file));
        }
        
        return $comps;
    }

}
