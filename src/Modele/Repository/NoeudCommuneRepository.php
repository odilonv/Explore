<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;
use App\PlusCourtChemin\Modele\DataObject\NoeudCommune;

class NoeudCommuneRepository extends AbstractRepository
{

    public function construireDepuisTableau(array $noeudRoutierTableau): NoeudCommune
    {
        return new NoeudCommune(
            $noeudRoutierTableau["gid"],
            $noeudRoutierTableau["id_rte500"],
            $noeudRoutierTableau["nom_comm"],
            $noeudRoutierTableau["id_nd_rte"]
        );
    }

    protected function getNomTable(): string
    {
        return 'noeud_commune';
    }

    protected function getNomClePrimaire(): string
    {
        return 'gid';
    }

    protected function getNomsColonnes(): array
    {
        return ["gid", "id_rte500", "nom_comm", "id_nd_rte"];
    }

    // On bloque l'ajout, la màj et la suppression pour ne pas modifier la table
    // Normalement, j'ai restreint l'accès à SELECT au niveau de la BD
    public function supprimer(string $valeurClePrimaire): bool
    {
        return false;
    }

    public function mettreAJour(AbstractDataObject $object): void
    {
        return;
    }

    public function ajouter(AbstractDataObject $object): bool
    {
        return false;
    }

}
