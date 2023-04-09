<?php

namespace Explore\Modele\DataObject\aStar;

use Explore\Lib\Utils;

class NoeudStar
{
    private string $gid;
    /* @var double[] $coords */
    private array $coords;
    private ?NoeudStar $precedentVoisin = null;

    private array $noeudsVoisins = [];

    private float $distanceDebut = PHP_FLOAT_MAX;
    private float $distanceFin;

    private EtatNoeud $state = EtatNoeud::PAUSE;


    /* @var double[] $coords*/
    public function __construct(string $gid, array $coords, float $distanceFin)
    {
        $this->coords = $coords;
        $this->distanceFin = $distanceFin;
        $this->gid = $gid;
    }

    /**
     * @return double[]
     */
    public function getCoords(): array
    {
        return $this->coords;
    }

    /**
     * @param float $distanceDebut
     */
    public function setDistanceDebut(float $distanceDebut): void
    {
        $this->distanceDebut = $distanceDebut;
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

    /**
     * @return array
     */
    public function getNoeudsVoisins(): array
    {
        return $this->noeudsVoisins;
    }

    public function getTotal():float
    {
        return $this->distanceDebut + $this->distanceFin;
    }

    /**
     * @return EtatNoeud
     */
    public function getState(): EtatNoeud
    {
        return $this->state;
    }

    /**
     * @param EtatNoeud $state
     */
    public function setState(EtatNoeud $state): void
    {
        $this->state = $state;
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
        if($this->distanceDebut > $nouvelleValeurDepuisVoisin){
            $this->distanceDebut = $nouvelleValeurDepuisVoisin;
            $this->precedentVoisin = $voisin;
        }
    }

    public function selectionner(){
        $this->state = EtatNoeud::VERIFIE;
        foreach ($this->noeudsVoisins as $infos) {
            $infos['voisin']->recalculer($this->distanceDebut + $infos['distance'], $this);
        }
    }

    public function refaireChemin():array
    {
        $chemin = [$this];
        $noeud = $this;
        $c = 0;
        while(isset($noeud->precedentVoisin)){
            $chemin[] = $noeud->precedentVoisin;
            $c++;
            $noeud = $noeud->precedentVoisin;
        }

        return $chemin;
    }
}