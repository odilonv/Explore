<?php

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
    )
    {
    }

    public function calculer(bool $affichageDebug = false): float
    {
        $noeudRoutierRepository = new NoeudRoutierRepository();

        // Distance en km, table indexé par NoeudRoutier::gid
        $this->distances = [$this->noeudRoutierDepartGid => 0];

        $this->noeudsALaFrontiere[$this->noeudRoutierDepartGid] = true;

        $precharges = $noeudRoutierRepository->getInRange($this->noeudRoutierDepartGid, 50);

        while (count($this->noeudsALaFrontiere) !== 0) {
            $noeudRoutierGidCourant = $this->noeudALaFrontiereDeDistanceMinimale();

            // Fini
            if ($noeudRoutierGidCourant === $this->noeudRoutierArriveeGid) {
                return $this->distances[$noeudRoutierGidCourant];
            }

            // Enleve le noeud routier courant de la frontiere
            unset($this->noeudsALaFrontiere[$noeudRoutierGidCourant]);

            /** @var NoeudRoutier $noeudRoutierCourant */
            $noeudRoutierCourant = $noeudRoutierRepository->recupererParClePrimaire($noeudRoutierGidCourant);

            $voisins = $noeudRoutierCourant->getVoisins();

            foreach ($voisins as $voisin) {
                $noeudVoisinGid = $voisin["noeud_routier_gid"];
                $distanceTroncon = $voisin["longueur"];
                $distanceProposee = $this->distances[$noeudRoutierGidCourant] + $distanceTroncon;

                if (!isset($this->distances[$noeudVoisinGid]) || $distanceProposee < $this->distances[$noeudVoisinGid]) {
                    $this->distances[$noeudVoisinGid] = $distanceProposee;
                    $this->noeudsALaFrontiere[$noeudVoisinGid] = true;
                }
            }
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

    public function calculer2()
    {
        $this->gidParcours = new gidParcours();
        $this->cache = (new NoeudRoutierRepository())->getInRange("0101000020E61000000112967685E2E03FEE072CA6DD674540", 1000); // s'assurer que la méthode marche pleinement
        $this->gidParcours->addToParcours($this->noeudRoutierDepartGid);
        //        $this->gidAParcourir[] = $this->noeudRoutierDepartGid;
        $this->noeudsDistance = [];
        //        $this->gidParcouru = [];

        foreach ($this->cache->getInfos() as $key => $value) {
            $this->noeudsDistance[$key] = PHP_FLOAT_MAX;
        }
        $this->noeudsDistance[$this->noeudRoutierDepartGid] = 0;

        while ($this->gidParcours->hasNext()) {
            $this->gidCourant = $this->gidParcours->next();

            $this->actualiserDistanceMinimal();

            if ($this->gidCourant == $this->noeudRoutierArriveeGid) {
                (new NoeudRoutierRepository())->getForStar("0101000020E61000000112967685E2E03FEE072CA6DD674540", "0101000020E61000000112967685E2E03FEE072CA6DD674540");
                return $this->noeudsDistance[$this->noeudRoutierArriveeGid];
            }
        }
        return 0;
    }

    private function actualiserDistanceMinimal()
    {
        $distCourant = $this->noeudsDistance[$this->gidCourant];
        foreach ($this->cache->getVoisins($this->gidCourant) as $gidDepart => $values) {
            $gidVoisin = $values['voisin'];
            $longueur = $values['longueur'];
            $this->gidParcours->addToParcours($gidVoisin);

            if (!isset($this->noeudsDistance[$gidVoisin])) $this->noeudsDistance[$gidVoisin] = PHP_FLOAT_MAX;

            if ($distCourant + $longueur < $this->noeudsDistance[$gidVoisin]) {
                $this->noeudsDistance[$gidVoisin] = $distCourant + $longueur;
            }
        }
    }
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