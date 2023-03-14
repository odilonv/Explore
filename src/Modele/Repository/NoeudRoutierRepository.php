<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Lib\Utils;
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
     * pour chaque voisin, le gid du voisin est une clé associé au gid du troncon ainsi que la longueur
     * [ gidVoisin => [gidNR, gidTR, longueur]]
     */
    public function getInRange(string $gidCentre, float $range): array
    {
        $requeteSQL = <<<SQL
        select gidOrigine as gid, nrB.id_rte500, gidVoisin, gidTR, longueur
        from vue_voisins vv
        join noeud_routier nrA on nrA.gid=:gidCentre
        join noeud_routier nrB on vv.gidOrigine=nrB.gid
        where st_distancesphere(nrA.geom, nrB.geom)<:range
        order by gidOrigine;
        SQL;
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($requeteSQL);

        $pdoStatement->execute(
            ['gidCentre'=>$gidCentre,
                'range'=>$range]);

        // array simple pour stocker tous les noeuds routier concerné
        $noeuds_routiers = [];
        $previousNRInfos = [
            'gid'=>'',
            'id_rte500'=>'',
            'voisins'=>[]
        ];
        foreach ($pdoStatement as $rowValue){
            // les valeurs ne concernent plus le meme noeud
            // dans ce cas on commence a accumuler les infos pour le noeud d'après et on instancie l'ancien noeud si possible
            if($rowValue['gid']!=$previousNRInfos['gid']){
                if($previousNRInfos['gid']!='') {
                    $nr = new NoeudRoutier($previousNRInfos['gid'],
                        $previousNRInfos['id_rte500'],
                        $previousNRInfos['voisins']);
                    $noeuds_routiers[$nr->getGid()] = $nr;
                }
                $previousNRInfos['gid'] = $rowValue['gid'];
                $previousNRInfos['id_rte500'] = $rowValue['id_rte500'];
                $previousNRInfos['voisins'] = [];
            }
            // memes infos que pour getVoisins
            // `noeud_routier_gid`, `troncon_gid`, `longueur`
            $previousNRInfos['voisins'][$rowValue['gidvoisin']] = ['noeud_routier_gid'=>$rowValue['gidvoisin']
                                            , 'troncon_gid' => $rowValue['gidtr']
                                            , 'longueur' => $rowValue['longueur']];
        }
        return $noeuds_routiers;
    }
}
