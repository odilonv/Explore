<?php

namespace Explore\Modele\Repository;

use Explore\Modele\DataObject\AbstractDataObject;
use Explore\Modele\DataObject\NoeudCommune;
use Explore\Modele\Repository\AbstractRepository;
use Explore\Modele\Repository\ConnexionBaseDeDonneesInterface;

class NoeudCommuneRepository extends AbstractRepository implements NoeudCommuneRepositoryInterface
{


    public function __construct(ConnexionBaseDeDonneesInterface $connexionBaseDeDonnees)
    {
        parent::__construct($connexionBaseDeDonnees);
    }

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

    public function getCommune($ville)
    {

        $requeteSQL = <<<SQL
        (select nom_comm
        from noeud_commune
        where nom_comm LIKE :ville_tag LIMIT 5);
        SQL;
        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($requeteSQL);
        $pdoStatement->execute(array(
            "ville_tag" => $ville."%"
        ));
        return $pdoStatement->fetchAll($this->connexionBaseDeDonnees->getPdo()::FETCH_ASSOC);
    }

    public function recupererParProximite(float $lat, float $lng): ?string
    {
        $requeteSQL = <<<SQL
        (select nom_comm
        from noeud_commune
        order by (geom <-> st_setSrid(st_makepoint(:lng, :lat), 4326))
        limit 1);
        SQL;

        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($requeteSQL);
        $pdoStatement->execute(['lat'=>$lat, 'lng'=>$lng]);

        return $pdoStatement->fetch()['nom_comm'];
    }


}
