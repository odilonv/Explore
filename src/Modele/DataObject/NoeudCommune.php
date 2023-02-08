<?php

namespace App\PlusCourtChemin\Modele\DataObject;

class NoeudCommune extends AbstractDataObject
{
    public function __construct(
        private int $gid,
        private string $id_rte500,
        private string $nomCommune,
        private string $id_nd_rte,
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

    public function getId_nd_rte(): string
    {
        return $this->id_nd_rte;
    }

    public function getNomCommune(): string
    {
        return $this->nomCommune;
    }

    public function exporterEnFormatRequetePreparee(): array
    {
        // Inutile car on ne fait pas d'ajout ni de mise-à-jour
        return [];
    }
}
