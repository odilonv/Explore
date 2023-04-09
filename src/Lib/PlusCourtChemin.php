<?php

namespace Explore\Lib;

use Explore\Configuration\ConfigurationBDDPostgreSQL;
use Explore\Modele\DataObject\aStar\NoeudStar;
use Explore\Modele\Repository\NoeudRoutierRepositoryInterface;

class PlusCourtChemin
{
    private array $distances;
    private array $noeudsALaFrontiere;
    private NoeudRoutierRepositoryInterface $noeudRoutierRepository;

    public function __construct(
        private int $noeudRoutierDepartGid,
        private int $noeudRoutierArriveeGid,
        NoeudRoutierRepositoryInterface $noeudRoutierRepository
    ) {
        $this->noeudRoutierRepository=$noeudRoutierRepository;
    }

    public function calculer3():?NoeudStar{
        $queuStar = $this->noeudRoutierRepository->getForStar($this->noeudRoutierDepartGid, $this->noeudRoutierArriveeGid);

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