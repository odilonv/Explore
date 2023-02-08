<?php

namespace App\PlusCourtChemin\Modele\DataObject;

use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;
use Exception;

class NoeudRoutier extends AbstractDataObject
{
    private array $voisins;

    public function __construct(
        private int $gid,
        private string $id_rte500,
    ) {
        $this->voisins = (new NoeudRoutierRepository())->getVoisins($this->getGid());
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
        return $this->voisins;
    }

    public function exporterEnFormatRequetePreparee(): array
    {
        // Inutile car pas d'ajout ni de màj
        throw new Exception("Vous ne devriez pas appeler cette fonction car il n'y a pas de modification des noeuds routiers");
        return [];
    }
}
