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
        select st_x(geom) as x, st_y(geom) as y, gid, geom
        from noeud_routier
        where gid = :gidDepart OR gid = :gidArrivee;
        SQL;

        $pdoStatement = $pdo->prepare($requeteXY);
        $pdoStatement->execute(['gidDepart' => $gidDep, 'gidArrivee' => $gidArrivee]);
        $coordonnees = $pdoStatement->fetchAll();

        $geomArrivee = '';
        if($coordonnees[0][2] == $gidArrivee){$geomArrivee=$coordonnees[0][3];}else{$geomArrivee=$coordonnees[1][3];}
//        $vecteurAB = ["x" => $coordonnees[0][0]-$coordonnees[1][0],
//                    "y" => $coordonnees[0][1]-$coordonnees[1][1]];
//
//        $ABRotated = ['x' => $vecteurAB['y'], 'y' => -$vecteurAB['x']];
//
//        $origine = ['x' => $coordonnees[1][0], 'y' => $coordonnees[1][1]];
//        $agrandissement = ['x' => 1, 'y' => 1];
//        $pt1 = ['x' => $origine['x'] - $ABRotated['x']/2, 'y' => $origine['y'] - $ABRotated['y']/2];
//        $pt2 = ['x' => $pt1['x'] + $vecteurAB['x'], 'y' => $pt1['y'] + $vecteurAB['y']];
//        $pt3 = ['x' => $pt2['x'] + $ABRotated['x'], 'y' => $pt2['y'] + $ABRotated['y']];
//        $pt4 = ['x' => $pt3['x'] - $vecteurAB['x'], 'y' => $pt3['y'] - $vecteurAB['y']];

        $requeteArea = <<<SQL
        select ST_MakePolygon( ST_GeomFromText(:points, 4326));
        SQL;
        $pdoStatement = $pdo->prepare($requeteArea);

        $points = $this->genererChaineZone(['x' =>$coordonnees[0][0], 'y' => $coordonnees[0][1]], ['x' => $coordonnees[1][0], 'y' => $coordonnees[1][1]]);

//        $points = 'LINESTRING(' . $pt1['x'] . ' ' . $pt1['y'] . ',' .
//        $pt2['x'] . ' ' . $pt2['y'] . ',' .
//        $pt3['x'] . ' ' . $pt3['y'] . ',' .
//        $pt4['x'] . ' ' . $pt4['y'] . ',' .
//        $pt1['x'] . ' ' . $pt1['y'] . ')';

        $pdoStatement->execute(
            ['points' => $points]);

        $area = $pdoStatement->fetch()[0];


        $starQueue = new QueueStar();
        $requeteDist = <<<SQL
            select gid, st_distancesphere(geom, :geomGoal) / 1000 as distanceFromGoal
            from noeud_routier nr
            where st_intersects(:areaGeom, geom);
        SQL;


        $pdoStatement = $pdo->prepare($requeteDist);
        $pdoStatement->execute(['geomGoal' => $geomArrivee,
                                'areaGeom' => $area]);

        $noeudsDist = [];
        $result = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $infos){
            $dist = $infos['distancefromgoal'];
            $gid = $infos['gid'];
            $noeud = new NoeudStar($gid, $dist);
            $noeudsDist[$gid] = $noeud;
            $noeud->setPrioQ($starQueue);
        }

        // regarder si st_x est exécuté pour chaque ligne dans la requete (l'optimiseur doit s'en  occuper jpense)
        $requeteSQL = <<<SQL
        select gidDepart, gidvoisin as gidvoisin, gidtr as troncon, longueur
        from voisins v 
        join noeud_routier nrA on nrA.gid=v.giddepart
        where st_intersects(:areaGeom, geom);
        SQL;

        $pdoStatement = $pdo->prepare($requeteSQL);
        $pdoStatement->execute(['areaGeom' => $area]);

        $result = $pdoStatement->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);
        foreach ($result as $key => $infos) {
            foreach ($infos as $voisin) {
                if (isset($noeudsDist[$key]) && isset($noeudsDist[$voisin['gidvoisin']])) {
                    $noeudsDist[$key]->addVoisin($noeudsDist[$voisin['gidvoisin']], $voisin['longueur'], $voisin['troncon']);
                }
            }
            if($key == $gidDep){
                $noeudsDist[$key]->setDistanceDebut(0);
                $starQueue->insert($noeudsDist[$key], $noeudsDist[$key]);
            }
        }
        return $starQueue;
    }

    public function genererChaineZone(array $startPoint, array $endPoint){

        $direction = ['x' => $endPoint['x']-$startPoint['x'], 'y' => $endPoint['y']-$startPoint['y']];
        $perpendiculaire = ['x' => $direction['y'], 'y' => -$direction['x']];

        $pt1 = ['x' => $startPoint['x'] - $direction['x'] - $perpendiculaire['x'], 'y' => $startPoint['y'] - $direction['y'] - $perpendiculaire['y']];
        $pt2 = ['x' => $pt1['x'] + $direction['x'] * 3, 'y' => $pt1['y'] + $direction['y'] * 3];
        $pt3 = ['x' => $pt2['x'] + $perpendiculaire['x'] * 3, 'y' => $pt2['y'] + $perpendiculaire['y'] * 3];
        $pt4 = ['x' => $pt3['x'] - $direction['x'] * 3, 'y' => $pt3['y'] - $direction['y'] * 3];

        return 'LINESTRING(' . $pt1['x'] . ' ' . $pt1['y'] . ',' .
            $pt2['x'] . ' ' . $pt2['y'] . ',' .
            $pt3['x'] . ' ' . $pt3['y'] . ',' .
            $pt4['x'] . ' ' . $pt4['y'] . ',' .
            $pt1['x'] . ' ' . $pt1['y'] . ')';
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

