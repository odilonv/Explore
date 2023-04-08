<?php

namespace Explore\Modele\DataObject\aStar;

class QueueStar{
    /** @var NoeudStar[] $t */
    private array $t;

    public function __construct()
    {
        $this->t = [];
    }

    private function swap(int $a, int $b){
        $temp = $this->t[$a];
        $this->t[$a] = $this->t[$b];
        $this->t[$b] = $temp;
    }

    private function left(int $i):int
    {
        return 2 * $i + 1;
    }
    private function right(int $i):int{
        return 2 * $i +2;
    }
    private function father(int $i):int
    {
        return ($i-1) / 2;
    }

    public function getSize():int
    {
        return sizeof($this->t);
    }

    public function isInf(NoeudStar $a, NoeudStar $b):bool{
        $diffTotal = $a->getTotal() - $b->getTotal();
        if($diffTotal < 0){
            return true;
        }
        elseif ($diffTotal == 0){
            $diffFin = $a->getDistanceFin() - $b->getDistanceFin();
            if($diffFin < 0){
                return true;
            }
            elseif ($diffFin == 0){
                $diffDebut = $a->getDistanceDebut() - $b->getDistanceDebut();
                if($diffDebut < 0){
                    return true;
                }
            }
        }
        return false;
    }

    public function heapifyUp(int $i){
        $parent = $this->father($i);
        if($i>0 && $this->isInf($this->t[$i], $this->t[$parent]))
        {
            $this->swap($i, $parent);
            $this->heapifyUp($parent);
        }
    }

    public function heapifyDown(int $i){
        $filsG = $this->left($i);
        $filsD = $this->right($i);

        if ($filsD>=sizeof($this->t))return;

        $indexCompare = $this->isInf($this->t[$filsG], $this->t[$filsD]) ? $filsG : $filsD;
        if($this->isInf($this->t[$indexCompare], $this->t[$i])){
            $this->swap($indexCompare, $i);
            $this->heapifyDown($indexCompare);
        }
    }

    public function getTop():NoeudStar
    {
        return $this->t[0];
    }

    public function removeTop() : NoeudStar
    {
        return $this->remove(0);
    }

    public function remove(int $i) : NoeudStar
    {
        $value = $this->t[$i];

        $this->swap($i, sizeof($this->t) - 1);
        unset($this->t[sizeof($this->t)-1]);
        $this->heapifyDown($i);

        return $value;
    }

    public function insert(NoeudStar $noeud){
        $this->t[sizeof($this->t)] = $noeud;
        $this->heapifyUp(sizeof($this->t)-1);
    }
}