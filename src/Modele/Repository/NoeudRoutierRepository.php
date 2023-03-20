<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Lib\Utils;
use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;
use App\PlusCourtChemin\Modele\DataObject\CacheNR;
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
     * pour chaque voisin, le gid du voisin est une clé associé au gid du troncon ainsi que la longueur
     * [ gidVoisin => [gidTR, longueur]]
     *
     * @param int $noeudRoutierGid
     * @return String[][]
     **/
    public function getVoisins(int $noeudRoutierGid): array
    {
        $requeteSQL = <<<SQL
        (select gidA as noeud_routier_gid, gidTR as troncon_gid, longueur
        from areteGID 
        where gidB=:gidTag
        union
        select gidB as noeud_routier_gid, gidTR as troncon_gid, longueur
        from areteGID
        where gidA=:gidTag);
        SQL;
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($requeteSQL);
        $pdoStatement->execute(array(
            "gidTag" => $noeudRoutierGid
        ));
        Utils::log("methode getVoisin de nrRepo appellé (pas opti)");
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $gidCentre
     * @param float $range
     * @return array
     * range est le rayon en metre
     * pour chaque voisin, le gid du voisin est une clé associé au gid du troncon ainsi que la longueur
     * [ gidVoisin => [gidNR, gidTR, longueur]]
     */
    public function getInRange(string $geomCentre, float $range): CacheNR
    {
        $requeteSQL = <<<SQL
        select gidDepart, gidvoisin as voisin, gidTR as troncon, longueur
        from voisins
        where sqrt(pow(st_x(:geomCentre)-x, 2) + pow(st_y(:geomCentre)-y, 2))<:range * 100000
        SQL;
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($requeteSQL);

        $pdoStatement->execute(
            ['geomCentre'=>$geomCentre,
                'range'=>$range]);

        $cache = new CacheNR();
        $cache->setInfosPDO($pdoStatement->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP));
        /*foreach ($pdoStatement as $rowValue){
            $cache->addInfo($rowValue['giddepart'], $rowValue['gidvoisin'], $rowValue['gidtr'], $rowValue['longueur']);
        }*/
        return $cache;
    }
}
