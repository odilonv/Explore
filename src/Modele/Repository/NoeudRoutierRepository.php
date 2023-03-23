<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Lib\Utils;
use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;
use App\PlusCourtChemin\Modele\DataObject\aStar\NoeudStar;
use App\PlusCourtChemin\Modele\DataObject\aStar\QueueStar;
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

        $ts = Utils::getDuree();
        $pdoStatement->execute(
            ['geomCentre'=>$geomCentre,
                'range'=>$range]);
        Utils::log("temps requete: " . Utils::getDuree()-$ts);

        $cache = new CacheNR();
        $ts = Utils::getDuree();
        $cache->setInfosPDO($pdoStatement->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP));
        Utils::log("temps setCache: " . Utils::getDuree()-$ts . '  // taille cache: ' . sizeof($cache->getInfos()));

        return $cache;
    }

    // retourne ce qu'il faut pour pouvoir utiliser a star
    public function getForStar(string $gidDep, string $gidArrivee){
        $pdo = ConnexionBaseDeDonnees::getPdo();

        $requeteXY = <<<SQL
        select st_x(geom) as x, st_y(geom) as y, gid
        from noeud_routier
        where gid = :gidDepart OR gid = :gidArrivee;
        SQL;

        $pdoStatement = $pdo->prepare($requeteXY);
        $pdoStatement->execute(['gidDepart' => $gidDep, 'gidArrivee' => $gidArrivee]);
        $coordonnees = $pdoStatement->fetchAll();

        $left = null;
        $right = null;
        $top = null;
        $bottom = null;

        if($coordonnees[0][0] >= $coordonnees[1][0]){
            $right = $coordonnees[0][0];
            $left = $coordonnees[1][0];
        }
        else{
            $right = $coordonnees[1][0];
            $left = $coordonnees[0][0];
        }
        if($coordonnees[0][1] >= $coordonnees[1][1]){
            $bottom = $coordonnees[0][1];
            $top = $coordonnees[1][1];
        }
        else{
            $bottom = $coordonnees[1][1];
            $top = $coordonnees[0][1];
        }


        $starQueue = new QueueStar();
        $requeteDist = <<<SQL
            select gidDepart, (sqrt(pow(:xGoal - x, 2) + pow(:yGoal - y, 2))) as distanceFromGoal
            from voisins v
            where x>:top or x<:bottom or y>:left or y<:right;
        SQL;

        $pdoStatement = $pdo->prepare($requeteDist);
        $pdoStatement->execute(['xGoal' => $coordonnees[0][2]==$gidArrivee?$coordonnees[0][0]:$coordonnees[1][0],
                                'yGoal' => $coordonnees[0][2]==$gidArrivee?$coordonnees[0][1]:$coordonnees[1][1],
                                'left' => $left,
                                'right' => $right,
                                'top' => $top,
                                'bottom' => $bottom]);

        $noeudsDist = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        foreach($noeudsDist as $infos){
            $dist = $infos['distancefromgoal'];
            $gid = $infos['giddepart'];
            $noeud = new NoeudStar($gid, $dist);
            $noeudsDist[$gid] = $noeud;
            $noeud->setPrioQ($starQueue);
            if($starQueue->count()==0 && $gid==$gidDep){
                $starQueue->insert($noeudsDist[$gid], $noeudsDist[$gid]);
            }
        }

        // regarder si st_x est exécuté pour chaque ligne dans la requete (l'optimiseur doit s'en  occuper jpense)
        $requeteSQL = <<<SQL
        select gidDepart, gidvoisin as voisin, gidTR as troncon, longueur
        from voisins
        SQL;

        $pdoStatement = $pdo->prepare($requeteSQL);
        $pdoStatement->execute();

        $result = $pdoStatement->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);
        foreach ($result as $key => $infos){
            foreach ($infos as $voisin) {
                if(isset($noeudsDist[$key]))
                $noeudsDist[$key]->addVoisin($noeudsDist[$voisin['gidvoisin']], $voisin['longueur'], $voisin['gidtr']);
            }
        }

        return $starQueue;
    }
}

/*
 * un noeud c'est l'adresse de ses voisins, la valeur pour les join
 * définir la valeur du noeud: sa distance du départ + sa distance de l'arrivée
 * on la calcul dès qu'un voisin est sélectionné
 * un noeud a 4 états: complètement électionné, vérifié, en cours et pas vérifié
 *  - vérifié ne nécessite plus de mise a jour sur sa valeur
 *  - pas vérifié entrera dans la boucle si il est voisin de qqn qui vient d'être sélectionné
 *
 * on met a jour la valeur d'un noeud dès qu'un voisin est sélectionné
 *
 */

