<?php

namespace App\PlusCourtChemin\Modele\DataObject\aStar;

use App\PlusCourtChemin\Lib\Utils;

class NoeudStar
{
    private string $gid;
    private ?NoeudStar $precedentVoisin = null;

    private QueueStar $prioQ;

    private array $noeudsVoisins = [];

    private float $distanceDebut = PHP_FLOAT_MAX;
    private float $distanceFin;
    private float $valeurFinal = PHP_FLOAT_MAX;

    public function __construct(string $gid, float $distanceFin)
    {
        $this->distanceFin = $distanceFin;
        $this->gid = $gid;
    }

    /**
     * @param float $distanceDebut
     */
    public function setDistanceDebut(float $distanceDebut): void
    {
        $this->distanceDebut = $distanceDebut;
    }



    public function setPrioQ(QueueStar $p){
        $this->prioQ = $p;
    }

    /**
     * @return string
     */
    public function getGid(): string
    {
        return $this->gid;
    }


    /**
     * @return float
     */
    public function getDistanceDebut(): float
    {
        return $this->distanceDebut;
    }

    /**
     * @return float
     */
    public function getDistanceFin(): float
    {
        return $this->distanceFin;
    }

    public function getTotal():float
    {
        return $this->distanceDebut + $this->distanceFin;
    }


    public function addVoisin(NoeudStar $voisin, float $longueur, string $gidTR){
        $this->noeudsVoisins[] = ['voisin' => $voisin,
            'distance' => $longueur,
            'gidTR' => $gidTR];
    }

    // idée: cette fonction est appellé si un voisin recalcule sa valeur. dans ce cas ce noeud recalcul sa valeur et préviens ses voisins
    // problème: très vite les appels risquent de se multiplier
    // methode appellé depuis les autres noeuds
    public function recalculer(float $nouvelleValeurDepuisVoisin, NoeudStar $voisin){
        if($this->distanceDebut == PHP_FLOAT_MAX){
            $this->prioQ->insert($this, $this);
            $this->distanceDebut = $nouvelleValeurDepuisVoisin;
            $this->precedentVoisin = $voisin;
        }
        $dd = $this->distanceDebut;
        if($dd > $nouvelleValeurDepuisVoisin){
            $this->distanceDebut = $nouvelleValeurDepuisVoisin;
            $this->precedentVoisin = $voisin;
            foreach ($this->noeudsVoisins as $infos) {
                if($infos['voisin']->gid != $voisin->gid)
                $infos['voisin']->recalculer($this->distanceDebut + $infos['distance'], $this);
            }
        }
    }

    public function selectionner(){
        $this->valeurFinal = $this->distanceDebut + $this->distanceFin;
        foreach ($this->noeudsVoisins as $infos) {
            $infos['voisin']->recalculer($this->distanceDebut + $infos['distance'], $this);
        }
    }

    /**
     * @return array
     */
    public function getNoeudsVoisins(): array
    {
        return $this->noeudsVoisins;
    }

    public function refaireChemin():array
    {
        $chemin = [];
        $noeud = $this;
        while(isset($noeud->precedentVoisin)){
            $chemin[] = $noeud;
            $noeud = $this->precedentVoisin;
        }

        return $chemin;
    }
}