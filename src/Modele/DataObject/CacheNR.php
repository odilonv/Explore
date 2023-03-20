<?php

namespace App\PlusCourtChemin\Modele\DataObject;

use App\PlusCourtChemin\Lib\Utils;

class CacheNR
{
    private array $infos;

    public function setInfosPDO(array $values):void{
        $this->infos = $values;
    }

    public function addInfo(string $gidA, string $gidVoisin, string $gidTR, float $longueur){
        $this->infos[$gidA][] = ["voisin" => $gidVoisin,
                                "troncon" => $gidTR,
                                "longueur" => $longueur];
    }

    public function getVoisins(string $gidDepart): array{
        $result = [];
        if(!isset($this->infos[$gidDepart])){Utils::log("le noeud n'es pas chargé " . $gidDepart);}
        else {
            foreach ($this->infos[$gidDepart] as $inf) {
                $result[] = $inf;
            }
        }
        return $result;
    }

    public function getInfos():array{
        return $this->infos;
    }
}