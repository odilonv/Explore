<?php

namespace App\PlusCourtChemin\Modele\DataObject;


class TronconRoute extends AbstractDataObject
{

    public function __construct(
        private int $gid,
        private string $id_rte500,
        private string $sens,
        private string $numeroRoute,
        private float $longueur,
    ) {
    }

    public function getGid(): int
    {
        return $this->gid;
    }

    public function getId_rte500(): string
    {
        return $this->id_rte500;
    }

    public function getSens(): string
    {
        return $this->sens;
    }

    public function getNumeroRoute(): string
    {
        return $this->numeroRoute;
    }

    public function getLongueur(): float
    {
        return $this->longueur;
    }

    public function exporterEnFormatRequetePreparee(): array
    {
        // Inutile car pas d'ajout ni de màj
        return [];
    }
}
