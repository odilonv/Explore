<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;
use App\PlusCourtChemin\Modele\DataObject\NoeudRoutier;
use PDO;

class NoeudRoutierRepository extends AbstractRepository
{

    public function construireDepuisTableau(array $noeudRoutierTableau): NoeudRoutier
    {
        return new NoeudRoutier(
            $noeudRoutierTableau["gid"],
            $noeudRoutierTableau["id_rte500"],
            null
        );
    }

    protected function getNomTable(): string
    {
        return 'noeud_routier';
    }

    protected function getNomClePrimaire(): string
    {
        return 'gid';
    }

    protected function getNomsColonnes(): array
    {
        return ["gid", "id_rte500"];
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

    /**
     * Renvoie le tableau des voisins d'un noeud routier
     *
     * Chaque voisin est un tableau avec les 3 champs
     * `noeud_routier_gid`, `troncon_gid`, `longueur`
     *
     * @param int $noeudRoutierGid
     * @return String[][]
     **/
    public function getVoisins(int $noeudRoutierGid): array
    {
        $requeteSQL = <<<SQL
            (select  nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from noeud_routier nr, troncon_route tr, noeud_routier nr2
            where (st_distancesphere(nr.geom, st_startpoint(tr.geom)) < 1
                and st_distancesphere(nr2.geom, st_endpoint(tr.geom)) < 1
                and  nr.gid = :gidTag)
            )
            union
            (select  nr2.gid as noeud_routier_gid, tr.gid as troncon_gid, tr.longueur
            from noeud_routier nr, troncon_route tr, noeud_routier nr2
            where (st_distancesphere(nr2.geom, st_startpoint(tr.geom)) < 1
                and st_distancesphere(nr.geom, st_endpoint(tr.geom)) < 1
                and  nr.gid = :gidTag)
            );
        SQL;
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($requeteSQL);
        $pdoStatement->execute(array(
            "gidTag" => $noeudRoutierGid
        ));
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }
}
