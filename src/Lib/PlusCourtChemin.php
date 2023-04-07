<?php

namespace Explore\Lib;

use Explore\Modele\Repository\NoeudRoutierRepository;
use Explore\Modele\DataObject\aStar\NoeudStar;

class PlusCourtChemin
{
    private array $distances;
    private array $noeudsALaFrontiere;

    public function __construct(
        private int $noeudRoutierDepartGid,
        private int $noeudRoutierArriveeGid
    ) {
    }

    public function calculer3():NoeudStar{
        $prio = (new NoeudRoutierRepository())->getForStar($this->noeudRoutierDepartGid, $this->noeudRoutierArriveeGid);

        $dernierNoeud = null;
        do{
            $dernierNoeud = $prio->getTop();
            $dernierNoeud->selectionner();

            $prio->removeTop();
        }
        while($prio->getSize()>0 && $dernierNoeud->getGid() != $this->noeudRoutierArriveeGid);
        return $dernierNoeud;
    }

    // liste qui associe pour chaque pts, la distance la plus courte qui le relie au point d'origine
    // parcourir la liste des voisins du pt dont la distance et la plus courte et qui n'a pas encore été parcouru
    // si la longueur de l'arete qui relie le pt au voisin + la distance du pt < distance minimal voisin -> sa distance minimale devient ce point
}