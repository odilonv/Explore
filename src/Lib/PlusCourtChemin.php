<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\DataObject\NoeudRoutier;
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

    public function calculer(bool $affichageDebug = false): float
    {
        Utils::startTimer();
        $noeudRoutierRepository = new NoeudRoutierRepository();

        // Distance en km, table indexé par NoeudRoutier::gid
        $this->distances = [$this->noeudRoutierDepartGid => 0];

        $this->noeudsALaFrontiere[$this->noeudRoutierDepartGid] = true;

        $precharges = $noeudRoutierRepository->getInRange($this->noeudRoutierDepartGid, 50);

        while (count($this->noeudsALaFrontiere) !== 0) {
            Utils::log(' while: <br>[');
            $deb = Utils::getDuree();
            $noeudRoutierGidCourant = $this->noeudALaFrontiereDeDistanceMinimale();

            // Fini
            if ($noeudRoutierGidCourant === $this->noeudRoutierArriveeGid) {
                return $this->distances[$noeudRoutierGidCourant];
            }

            // Enleve le noeud routier courant de la frontiere
            unset($this->noeudsALaFrontiere[$noeudRoutierGidCourant]);

            /** @var NoeudRoutier $noeudRoutierCourant */
            $noeudRoutierCourant = $noeudRoutierRepository->recupererParClePrimaire($noeudRoutierGidCourant);
            Utils::log('  juste apres select noeud : ' . (Utils::getDuree() - $deb));
            $voisins = $noeudRoutierCourant->getVoisins();

            Utils::log('  av foreach : ' . (Utils::getDuree() - $deb));
            foreach ($voisins as $voisin) {
                $noeudVoisinGid = $voisin["noeud_routier_gid"];
                $distanceTroncon = $voisin["longueur"];
                $distanceProposee = $this->distances[$noeudRoutierGidCourant] + $distanceTroncon;

                if (!isset($this->distances[$noeudVoisinGid]) || $distanceProposee < $this->distances[$noeudVoisinGid]) {
                    $this->distances[$noeudVoisinGid] = $distanceProposee;
                    $this->noeudsALaFrontiere[$noeudVoisinGid] = true;
                }
            }
            Utils::log('  total : ' . (Utils::getDuree() - $deb) . '<br>]');
        }
        return 0;
    }

    private function noeudALaFrontiereDeDistanceMinimale()
    {
        $noeudRoutierDistanceMinimaleGid = -1;
        $distanceMinimale = PHP_INT_MAX;
        foreach ($this->noeudsALaFrontiere as $noeudRoutierGid => $valeur) {
            if ($this->distances[$noeudRoutierGid] < $distanceMinimale) {
                $noeudRoutierDistanceMinimaleGid = $noeudRoutierGid;
                $distanceMinimale = $this->distances[$noeudRoutierGid];
            }
        }
        return $noeudRoutierDistanceMinimaleGid;
    }


    // fct pour actualiser les distances de tous les pts par rapport à un noeud

    private array $gidAParcourir;
    private array $gidParcouru;

    // [gid => distance]
    private array $noeudsDistance;
    //
    private array $cache;
    private NoeudRoutier $nrCourant;

    public function calculer2(){
        $this->cache = (new NoeudRoutierRepository())->getInRange($this->noeudRoutierDepartGid, 500); // s'assurer que la méthode marche pleinement
        $this->gidAParcourir[] = $this->noeudRoutierDepartGid;
        $this->noeudsDistance = [];
        $this->gidParcouru = [];

        foreach ($this->cache as $key => $value){
            $this->noeudsDistance[$key] = PHP_FLOAT_MAX;
        }
        $this->noeudsDistance[$this->noeudRoutierDepartGid] = 0;

        while(sizeof($this->gidAParcourir)>0){
            $this->nrCourant = $this->cache[$this->gidAParcourir[0]];

            $this->actualiserDistanceMinimal();


            if($this->nrCourant->getGid() == $this->noeudRoutierArriveeGid){
                return $this->noeudsDistance[$this->noeudRoutierArriveeGid];
            }

            $this->gidParcouru[] = $this->gidAParcourir[0];
            unset($this->gidAParcourir[0]);

            $this->actualiserDistanceMinimal();
        }
        return 0;
    }

    private function actualiserDistanceMinimal(){
        $distCourant = $this->noeudsDistance[$this->nrCourant->getGid()];
        foreach ($this->nrCourant->getVoisins() as $gid => $values){
            $gidNR = $values['noeud_routier_gid'];
            $gidTR = $values['troncon_gid'];
            $longueur = $values['longueur'];
            if(!in_array($gid, $this->gidAParcourir) && !in_array($gid, $this->gidParcouru)){
                $this->gidAParcourir[$gid] = $gid;
            }

            var_dump($this->noeudsDistance);
            if($distCourant + $longueur<$this->noeudsDistance[$gid]){
                $this->noeudsDistance[$gid] = $distCourant + $longueur;
            }
        }
    }

    // liste qui associe pour chaque pts, la distance la plus courte qui le relie au point d'origine
    // parcourir la liste des voisins du pt dont la distance et la plus courte et qui n'a pas encore été parcouru
    // si la longueur de l'arete qui relie le pt au voisin + la distance du pt < distance minimal voisin -> sa distance minimale devient ce point
}
