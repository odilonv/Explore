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
        $this->cache = (new NoeudRoutierRepository())->getInRange($this->noeudRoutierDepartGid, 50);
        $this->gidAParcourir = [$this->noeudRoutierDepartGid => true];
        $this->noeudsDistance = [$this->noeudRoutierDepartGid => 0];
        $this->gidParcouru = [];

        while(sizeof($this->gidAParcourir)>0){

            foreach ($this->noeudsDistance as $gid=>$distance){
                if(in_array($gid, $this->gidAParcourir)){
                    if(isset($this->cache[$gid])){
                        $this->nrCourant = $this->cache[$gid];
                    }
                    break;
                }
            }

            unset($this->gidAParcourir[$this->nrCourant->getGid()]);
            $this->gidParcouru[$this->nrCourant->getGid()] = true;

            $this->actualiserDistanceMinimal();

            if($this->nrCourant->getGid() == $this->noeudRoutierArriveeGid){
                return $this->noeudsDistance[$this->noeudRoutierArriveeGid];
            }
        }
    }

    private function actualiserDistanceMinimal(){
        $distCourant = $this->noeudsDistance[$this->nrCourant->getGid()];
        foreach ($this->nrCourant->getVoisins() as $gid => $infos){
            if(!in_array($gid, $this->gidAParcourir) && !in_array($gid, $this->gidParcouru)){
                $this->gidAParcourir[$gid] = true;
            }
            if(!in_array($gid, $this->noeudsDistance)){
                $this->noeudsDistance[$gid] = PHP_FLOAT_MAX;
            }
            if($distCourant + $infos['longueur']<$this->noeudsDistance[$gid]){
                $this->noeudsDistance[$gid] = $distCourant + $infos['longueur'];
            }
        }
        asort($this->noeudsDistance);
    }

    // liste qui associe pour chaque pts, la distance la plus courte qui le relie au point d'origine
    // parcourir la liste des voisins du pt dont la distance et la plus courte et qui n'a pas encore été parcouru
    // si la longueur de l'arete qui relie le pt au voisin + la distance du pt < distance minimal voisin -> sa distance minimale devient ce point
}
