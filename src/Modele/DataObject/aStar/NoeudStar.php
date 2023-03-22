<?php

class NoeudStar extends \SAE\Lib\ObserverPattern\Observable
{
    private string $gid;

    private EtatNoeud $etat = EtatNoeud::PAUSE; // possible: vérifié, possible, pause

    private array $noeudsVoisins;

    private float $distanceDebut = PHP_FLOAT_MAX;
    private float $distanceFin;
    private float $valeurFinal = PHP_FLOAT_MAX;

    public function __construct(string $gid, float $distanceFin)
    {
        $this->distanceFin = $distanceFin;
        $this->gid = $gid;
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

    public function notifyAll()
    {
        foreach ($this->observers as $observer){
            $observer->update($this->etat->value);
        }
    }


    public function addVoisin(NoeudStar $voisin, float $longueur, string $gidTR){
        $this->noeudsVoisins[] = ['voisin' => $voisin,
            'distance' => $longueur,
            'gidTR' => $gidTR];
    }

    // idée: cette fonction est appellé si un voisin recalcule sa valeur. dans ce cas ce noeud recalcul sa valeur et préviens ses voisins
    // problème: très vite les appels risquent de se multiplier
    // methode appellé depuis les autres noeuds
    public function recalculer(float $nouvelleValeurDepuisVoisin){
        if($this->etat == EtatNoeud::POSSIBLE) {
            $dd = $this->distanceDebut;
            if($dd > $nouvelleValeurDepuisVoisin){
                $this->distanceDebut = $nouvelleValeurDepuisVoisin;
                foreach ($this->noeudsVoisins as $infos) {
                    $infos['voisin']->recalculer($this->distanceDebut + $infos['distance']);
                }
            }
        }
        elseif ($this->etat == EtatNoeud::PAUSE){
            $this->distanceDebut = $nouvelleValeurDepuisVoisin;
        }
    }

    public function selectionner(){
        $this->valeurFinal = $this->distanceDebut + $this->distanceFin;
        $this->etat = EtatNoeud::VERIFIE;
        foreach ($this->noeudsVoisins as $infos) {
            $infos['voisin']->recalculer($this->distanceDebut + $infos['distance']);
        }
    }
}