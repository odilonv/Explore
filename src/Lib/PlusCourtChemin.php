<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\DataObject\aStar\NoeudStar;
use App\PlusCourtChemin\Modele\DataObject\aStar\QueueStar;
use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;

class PlusCourtChemin
{
    private array $distances;
    private array $noeudsALaFrontiere;

    public function __construct(
        private int $noeudRoutierDepartGid,
        private int $noeudRoutierArriveeGid
    ) {
    }

    public function calculer3():?NoeudStar{
        Utils::startTimer();
        $queuStar = new QueueStar();
        (new NoeudRoutierRepository())->getForStar($this->noeudRoutierDepartGid, $this->noeudRoutierArriveeGid, $queuStar);

        Utils::log("total getForStar() : " . Utils::getDuree());
        $temp = Utils::getDuree();


        $avg1 = 0;
        $avg2 = 0;
        $avg3 = 0;

        $dernierNoeud = null;
        do{
            $t = Utils::getDuree();

            $dernierNoeud = $queuStar->getTop();
            $t2 = Utils::getDuree();
            $avg1 += $t2-$t;

            $dernierNoeud->selectionner();

            $t = Utils::getDuree();
            $avg2 += $t-$t2;

            $queuStar->removeTop();
            $avg3 += Utils::getDuree()-$t;
        }
        while($queuStar->getSize()>0 && $dernierNoeud->getGid() != $this->noeudRoutierArriveeGid);

        Utils::log(
            "total étape 1: " . ($avg1) . "<br>" .
            "total étape 2: " . ($avg2) . "<br>" .
            "total étape 3: " . ($avg3));

        Utils::log("temps parcours queue: " . Utils::getDuree()-$temp);
        Utils::log("temps d'utilisation de calculer(): " . Utils::getDuree());
        return $dernierNoeud->getGid()==$this->noeudRoutierArriveeGid?$dernierNoeud:null;
    }

    // liste qui associe pour chaque pts, la distance la plus courte qui le relie au point d'origine
    // parcourir la liste des voisins du pt dont la distance et la plus courte et qui n'a pas encore été parcouru
    // si la longueur de l'arete qui relie le pt au voisin + la distance du pt < distance minimal voisin -> sa distance minimale devient ce point
}