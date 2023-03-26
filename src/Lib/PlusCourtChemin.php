<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\DataObject\CacheNR;
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
    private gidParcours $gidParcours;

    // [gid => distance]
    private array $noeudsDistance;
    //
    private CacheNR $cache;
    private NoeudRoutier $nrCourant;
    private string $gidCourant;

    public function calculer2(){
        $this->gidParcours = new gidParcours();
        $ts = Utils::getDuree();
        $this->cache = (new NoeudRoutierRepository())->getInRange("0101000020E61000000112967685E2E03FEE072CA6DD674540", 1000); // s'assurer que la méthode marche pleinement
        Utils::log("temps récup noeuds in range: " . Utils::getDuree()-$ts);
        $this->gidParcours->addToParcours($this->noeudRoutierDepartGid);
//        $this->gidAParcourir[] = $this->noeudRoutierDepartGid;
        $this->noeudsDistance = [];
//        $this->gidParcouru = [];

        foreach ($this->cache->getInfos() as $key => $value){
            $this->noeudsDistance[$key] = PHP_FLOAT_MAX;
        }
        $this->noeudsDistance[$this->noeudRoutierDepartGid] = 0;

        $ts = Utils::getDuree();
        while($this->gidParcours->hasNext()){
            $this->gidCourant = $this->gidParcours->next();

            $this->actualiserDistanceMinimal();

            if($this->gidCourant == $this->noeudRoutierArriveeGid){
                Utils::log("temps while: " . Utils::getDuree()-$ts);
                (new NoeudRoutierRepository())->getForStar("0101000020E61000000112967685E2E03FEE072CA6DD674540", "0101000020E61000000112967685E2E03FEE072CA6DD674540");
                return $this->noeudsDistance[$this->noeudRoutierArriveeGid];
            }
        }
        return 0;
    }

    private function actualiserDistanceMinimal(){
        $distCourant = $this->noeudsDistance[$this->gidCourant];
        foreach ($this->cache->getVoisins($this->gidCourant) as  $gidDepart => $values){
            $gidVoisin = $values['voisin'];
            $longueur = $values['longueur'];
            $this->gidParcours->addToParcours($gidVoisin);

            if(!isset($this->noeudsDistance[$gidVoisin]))$this->noeudsDistance[$gidVoisin] = PHP_FLOAT_MAX;

            if($distCourant + $longueur<$this->noeudsDistance[$gidVoisin]){
                $this->noeudsDistance[$gidVoisin] = $distCourant + $longueur;
            }
        }
    }

    public function calculer3(){
        $prio = (new NoeudRoutierRepository())->getForStar($this->noeudRoutierDepartGid, $this->noeudRoutierArriveeGid);

        $dernierNoeud = null;
        while($prio->valid() && $prio->current()->getGid() != $this->noeudRoutierArriveeGid){
            $dernierNoeud = $prio->current();
            $dernierNoeud->selectionner();

            $prio->recoverFromCorruption();
            $prio->next();
        }

        return $dernierNoeud->getDistanceDebut();
    }

    // liste qui associe pour chaque pts, la distance la plus courte qui le relie au point d'origine
    // parcourir la liste des voisins du pt dont la distance et la plus courte et qui n'a pas encore été parcouru
    // si la longueur de l'arete qui relie le pt au voisin + la distance du pt < distance minimal voisin -> sa distance minimale devient ce point
}

class gidParcours{
    private array $gids=[];
    private array $gidParcouru = [];//ptetre enlever
    private int $idx=-1;

    public function addToParcours(string $gid){
        if(!in_array($gid, $this->gids)) $this->gids[] = $gid;
    }
    public function getCurrentGid():string{
        return $this->gids[$this->idx];
    }
    public function next(){
        $this->idx++;
        return $this->getCurrentGid();
    }
    public function parcouru(string $gid):bool{
        return in_array($gid, $this->gidParcouru);
    }
    public function hasNext():bool{
        return $this->idx<sizeof($this->gids)-1;
    }
}