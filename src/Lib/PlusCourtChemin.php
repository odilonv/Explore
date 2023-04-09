<?php

namespace Explore\Lib;

use Explore\Modele\DataObject\aStar\EtatNoeud;
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
        Utils::startTimer();
        $queuStar = $this->noeudRoutierRepository->getForStar($this->noeudRoutierDepartGid, $this->noeudRoutierArriveeGid);

        $dernierNoeud = null;
        $tmp1 = 0;
        $tmp2 = 0;
        $tmp3 = 0;
        $tailleMax=1;
        do{
            $tailleMax=$tailleMax<$queuStar->getSize()?$queuStar->getSize():$tailleMax;
            $tmp = Utils::getDuree();
            $dernierNoeud = $queuStar->removeTop();
            $tmp1 += Utils::getDuree()-$tmp;

            $tmp = Utils::getDuree();
            $dernierNoeud->selectionner();
            $tmp2 += Utils::getDuree()-$tmp;

            $tmp = Utils::getDuree();
            foreach ($dernierNoeud->getNoeudsVoisins() as $infosVoisin){
                $voisin = $infosVoisin['voisin'];
                if($voisin->getState() == EtatNoeud::PAUSE){
                    $voisin->setState(EtatNoeud::POSSIBLE);
                    $queuStar->insert($voisin);
                }
            }
            $tmp3 += Utils::getDuree()-$tmp;
        }
        while($queuStar->getSize()>0 && $dernierNoeud->getGid() != $this->noeudRoutierArriveeGid);

        Utils::log(
            "(calculer) temps 1: $tmp1 <br>
             (calculer) temps 2: $tmp2 <br>
             (calculer) temps 3: $tmp3");
        Utils::log("taille max du tas: " . $tailleMax);

        Utils::log("total calculer : " . Utils::getDuree());

        return $dernierNoeud->getGid()==$this->noeudRoutierArriveeGid?$dernierNoeud:null;
    }

    // liste qui associe pour chaque pts, la distance la plus courte qui le relie au point d'origine
    // parcourir la liste des voisins du pt dont la distance et la plus courte et qui n'a pas encore été parcouru
    // si la longueur de l'arete qui relie le pt au voisin + la distance du pt < distance minimal voisin -> sa distance minimale devient ce point
}