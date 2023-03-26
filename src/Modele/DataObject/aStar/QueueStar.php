<?php

namespace App\PlusCourtChemin\Modele\DataObject\aStar;

class QueueStar extends \SplPriorityQueue{
    /**
     * @param NoeudStar $priority1
     * @param NoeudStar $priority2
     * @return int
     */
    public function compare(mixed $priority1, mixed $priority2): int
    {
        if($priority1->getTotal() - $priority2->getTotal() != 0){
            return (int) round($priority1->getTotal() - $priority2->getTotal());
        }
        else if($priority1->getDistanceFin() - $priority2->getDistanceFin() != 0) {
            return (int) round($priority1->getDistanceFin() - $priority2->getDistanceFin());
        }
        else{
            return (int) round($priority1->getDistanceDebut()-$priority2->getDistanceDebut());
        }
    }
}