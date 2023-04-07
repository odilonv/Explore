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
        $queuStar = new QueueStar();
        (new NoeudRoutierRepository())->getForStar($this->noeudRoutierDepartGid, $this->noeudRoutierArriveeGid, $queuStar);

        $dernierNoeud = null;
        do{
            $dernierNoeud = $queuStar->getTop();

            $dernierNoeud->selectionner();

            $queuStar->removeTop();
        }
        while($queuStar->getSize()>0 && $dernierNoeud->getGid() != $this->noeudRoutierArriveeGid);

        return $dernierNoeud->getGid()==$this->noeudRoutierArriveeGid?$dernierNoeud:null;
    }

    // liste qui associe pour chaque pts, la distance la plus courte qui le relie au point d'origine
    // parcourir la liste des voisins du pt dont la distance et la plus courte et qui n'a pas encore été parcouru
    // si la longueur de l'arete qui relie le pt au voisin + la distance du pt < distance minimal voisin -> sa distance minimale devient ce point
}