<?php

namespace Explore\Modele\DataObject;

use Explore\Modele\Repository\NoeudRoutierRepository;
use Exception;

class NoeudRoutier extends AbstractDataObject
{
    private array $voisins;

    public function __construct(
        private int $gid,
        private string $id_rte500,
        ?array $voisins
    ) {
        if($voisins==null){
            $this->voisins=[];
        }
        else {
            $this->voisins = $voisins;
        }
    }

    public function getGid(): int
    {
        return $this->gid;
    }

    public function getId_rte500(): string
    {
        return $this->id_rte500;
    }

    public function getVoisins(): array
    {
        if(sizeof($this->voisins) == 0){
            $this->voisins = (new NoeudRoutierRepository())->getVoisins($this->getGid());
        }
        return $this->voisins;
    }

    public function exporterEnFormatRequetePreparee(): array
    {
        // Inutile car pas d'ajout ni de màj
        throw new Exception("Vous ne devriez pas appeler cette fonction car il n'y a pas de modification des noeuds routiers");
        return [];
    }
}
