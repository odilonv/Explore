<?php

namespace Explore\Modele\Repository;

use Explore\Lib\Vector;
use Explore\Modele\DataObject\AbstractDataObject;
use Explore\Lib\vieux\CacheNR;

use Explore\Modele\DataObject\aStar\NoeudStar;
use Explore\Modele\DataObject\aStar\QueueStar;
use Explore\Modele\DataObject\NoeudRoutier;
use PDO;

class NoeudRoutierRepository extends AbstractRepository implements NoeudRoutierRepositoryInterface
{
    public function __construct(ConnexionBaseDeDonneesInterface $connexionBaseDeDonnees)
    {
        parent::__construct($connexionBaseDeDonnees);
    }


    public function construireDepuisTableau(array $noeudRoutierTableau): NoeudRoutier
    {
        return new NoeudRoutier(
            $noeudRoutierTableau["gid"],
            $noeudRoutierTableau["id_rte500"],
            null,
            $this
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
        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($requeteSQL);
        $pdoStatement->execute(array(
            "gidTag" => $noeudRoutierGid
        ));
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
        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($requeteSQL);

        $pdoStatement->execute(
            ['geomCentre'=>$geomCentre,
                'range'=>$range]);

        $cache = new CacheNR();
        $cache->setInfosPDO($pdoStatement->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP));

        return $cache;
    }

    // retourne ce qu'il faut pour pouvoir utiliser a star
    public function getForStar(string $gidDep, string $gidArrivee, QueueStar $starQueue){


        $requeteXY = <<<SQL
        select st_x(geom) as x, st_y(geom) as y, gid, geom
        from noeud_routier
        where gid = :gidDepart OR gid = :gidArrivee;
        SQL;

        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($requeteXY);
        $pdoStatement->execute(['gidDepart' => $gidDep, 'gidArrivee' => $gidArrivee]);
        $coordonnees = $pdoStatement->fetchAll();

        if($coordonnees[0][2] == $gidArrivee){$geomArrivee=$coordonnees[0][3];}else{$geomArrivee=$coordonnees[1][3];}

        $requeteArea = <<<SQL
        select ST_MakePolygon( ST_GeomFromText(:points, 4326));
        SQL;
        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($requeteArea);

        $points = $this->genererChaineZone(['x' =>$coordonnees[0][0], 'y' => $coordonnees[0][1]], ['x' => $coordonnees[1][0], 'y' => $coordonnees[1][1]]);


        $pdoStatement->execute(
            ['points' => $points]);

        $area = $pdoStatement->fetch()[0];


        $starQueue = new QueueStar();
        $requeteDist = <<<SQL
            select gid, latitude, longitude, st_distance(geom, :geomGoal) / 1000 as distanceFromGoal, gidvoisin, gidtr as troncon, longueur
            from noeud_routier nr
            join voisins v on v.gidDepart=gid
            where st_intersects(:areaGeom, geom);
        SQL;


        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($requeteDist);
        $pdoStatement->execute(['geomGoal' => $geomArrivee,
                                'areaGeom' => $area]);

        $noeudsDist = [];
        $result = $pdoStatement->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);
        foreach($result as $key=>$lstNoeuds){
            $infos = $lstNoeuds[0];
            $dist = $infos['distancefromgoal'];
            $gid = $key;
            $noeud = new NoeudStar($gid, ['latitude' => $infos['latitude'], 'longitude' => $infos['longitude']], $dist);
            $noeudsDist[$gid] = $noeud;
            $noeud->setPrioQ($starQueue);
        }

        foreach ($result as $key=>$lstNoeuds){
            foreach ($lstNoeuds as $voisin){
                if(isset($noeudsDist[$voisin['gidvoisin']])) {
                    $noeudsDist[$key]->addVoisin($noeudsDist[$voisin['gidvoisin']], $voisin['longueur'], $voisin['troncon']);
                }
            }
            if($key == $gidDep){
                $noeudsDist[$key]->setDistanceDebut(0);
                $starQueue->insert($noeudsDist[$key]);
            }
        }
    }

    public function genererChaineZone(array $startPoint, array $endPoint){
        $direction = new Vector($endPoint['x']-$startPoint['x'], $endPoint['y']-$startPoint['y']);
        $directionNorm = $direction->normalized();
        $directionNorm->x /= 4;
        $directionNorm->y /= 4;
        $perpendiculaire = new Vector($direction->y, -$direction->x, true);
        $perpendiculaire->x /= 2;
        $perpendiculaire->y /= 2;

        $pt1 = new Vector($startPoint['x'] - $directionNorm->x - $perpendiculaire->x, $startPoint['y'] - $directionNorm->y - $perpendiculaire->y);
        $pt2 = new Vector($pt1->x + $direction->x + $directionNorm->x * 2, $pt1->y + $direction->y + $directionNorm->y * 2);
        $pt3 = new Vector($pt2->x + $perpendiculaire->x * 3, $pt2->y + $perpendiculaire->y * 3);
        $pt4 = new Vector($pt3->x - $direction->x - $directionNorm->x * 2, $pt3->y - $direction->y - $directionNorm->y * 2);


        $vecString = 'LINESTRING(' . $pt1->x . ' ' . $pt1->y . ',' .
            $pt2->x . ' ' . $pt2->y . ',' .
            $pt3->x . ' ' . $pt3->y . ',' .
            $pt4->x . ' ' . $pt4->y . ',' .
            $pt1->x . ' ' . $pt1->y . ')';

        return $vecString;
    }
}

