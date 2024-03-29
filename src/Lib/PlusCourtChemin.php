<?php

namespace Explore\Lib;

use Explore\Modele\DataObject\aStar\EtatNoeud;
use Explore\Modele\DataObject\aStar\NoeudStar;
use Explore\Modele\Repository\NoeudRoutierRepositoryInterface;

class PlusCourtChemin implements PlusCourtCheminInterface
{
    private NoeudRoutierRepositoryInterface $noeudRoutierRepository;

    public function __construct(
        private int $noeudRoutierDepartGid,
        private int $noeudRoutierArriveeGid,
        NoeudRoutierRepositoryInterface $noeudRoutierRepository
    ) {
        $this->noeudRoutierRepository = $noeudRoutierRepository;
    }

    public function calculer3(): ?NoeudStar
    {
        $queuStar = $this->noeudRoutierRepository->getForStar($this->noeudRoutierDepartGid, $this->noeudRoutierArriveeGid);

        $dernierNoeud = null;
        do {
            $dernierNoeud = $queuStar->removeTop();

            $noeuds = $dernierNoeud->selectionner();

            foreach ($noeuds as $voisin){
                if($voisin->getState() == EtatNoeud::PAUSE){
                    $voisin->setState(EtatNoeud::POSSIBLE);
                    $queuStar->insert($voisin);
                }
            }
        }
        while($queuStar->getSize()>0 && $dernierNoeud->getGid() != $this->noeudRoutierArriveeGid);

        return $dernierNoeud->getGid() == $this->noeudRoutierArriveeGid ? $dernierNoeud : null;
    }

    // liste qui associe pour chaque pts, la distance la plus courte qui le relie au point d'origine
    // parcourir la liste des voisins du pt dont la distance et la plus courte et qui n'a pas encore été parcouru
    // si la longueur de l'arete qui relie le pt au voisin + la distance du pt < distance minimal voisin -> sa distance minimale devient ce point
}