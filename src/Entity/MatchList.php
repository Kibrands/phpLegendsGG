<?php

namespace App\Entity;

class MatchList {

    private $matches;
    private $startIndex;
    private $endIndex;
    private $totalGames;

    public function setMatches($matches) {
        $this->matches = $matches;
    }

    public function setStartIndex($startIndex) {
        $this->startIndex = $startIndex;
    }

    public function setEndIndex($endIndex) {
        $this->endIndex = $endIndex;
    }

    public function setTotalGames($totalGames) {
        $this->totalGames = $totalGames;
    }

    public function getMatches(): ?array {
        return $this->matches;
    }

    public function getStartIndex(): ?int {
        return $this->startIndex;
    }

    public function getEndIndex(): ?int {
        return $this->endIndex;
    }

    public function getTotalGames(): ?int {
        return $this->totalGames;
    }

}
