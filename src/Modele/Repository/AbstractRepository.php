<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;
use PDOException;

abstract class AbstractRepository
{
    protected abstract function getNomTable(): string;
    protected abstract function getNomClePrimaire(): string;
    protected abstract function getNomsColonnes(): array;
    protected abstract function construireDepuisTableau(array $objetFormatTableau): AbstractDataObject;

    /**
     * @param int|string $limit Nombre de réponses ("ALL" pour toutes les réponses)
     * @return AbstractDataObject[]
     */
    public function recuperer($limit = 200): array
    {
        $nomTable = $this->getNomTable();
        $champsSelect = implode(", ", $this->getNomsColonnes());
        $requeteSQL = <<<SQL
        SELECT $champsSelect FROM $nomTable LIMIT $limit;
        SQL;
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query($requeteSQL);

        $objets = [];
        foreach ($pdoStatement as $objetFormatTableau) {
            $objets[] = $this->construireDepuisTableau($objetFormatTableau);
        }

        return $objets;
    }

    /**
     * @param array $critereSelection ex: ["nomColonne" => valeurDeRecherche]
     * @return AbstractDataObject[]
     */
    public function recupererPar(array $critereSelection, $limit = 200): array
    {
        $nomTable = $this->getNomTable();
        $champsSelect = implode(", ", $this->getNomsColonnes());

        $partiesWhere = array_map(function ($nomcolonne) {
            return "$nomcolonne = :$nomcolonne";
        }, array_keys($critereSelection));
        $whereClause = join(',', $partiesWhere);

        $requeteSQL = <<<SQL
            SELECT $champsSelect FROM $nomTable WHERE $whereClause LIMIT $limit;
        SQL;
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($requeteSQL);
        $pdoStatement->execute($critereSelection);

        $objets = [];
        foreach ($pdoStatement as $objetFormatTableau) {
            $objets[] = $this->construireDepuisTableau($objetFormatTableau);
        }

        return $objets;
    }

    public function recupererParClePrimaire(string $valeurClePrimaire): ?AbstractDataObject
    {
        $nomTable = $this->getNomTable();
        $nomClePrimaire = $this->getNomClePrimaire();
        $sql = "SELECT * from $nomTable WHERE $nomClePrimaire=:clePrimaireTag";
        // Préparation de la requête
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "clePrimaireTag" => $valeurClePrimaire,
        );
        // On donne les valeurs et on exécute la requête
        $pdoStatement->execute($values);

        // On récupère les résultats comme précédemment
        // Note: fetch() renvoie false si pas de voiture correspondante
        $objetFormatTableau = $pdoStatement->fetch();

        if ($objetFormatTableau !== false) {
            return $this->construireDepuisTableau($objetFormatTableau);
        }
        return null;
    }

    public function supprimer(string $valeurClePrimaire): bool
    {
        $nomTable = $this->getNomTable();
        $nomClePrimaire = $this->getNomClePrimaire();
        $sql = "DELETE FROM $nomTable WHERE $nomClePrimaire= :clePrimaireTag;";
        // Préparation de la requête
        $pdoStatement = ConnexionBaseDeDonnees::getPDO()->prepare($sql);

        $values = array(
            "clePrimaireTag" => $valeurClePrimaire
        );

        // On donne les valeurs et on exécute la requête
        $pdoStatement->execute($values);

        // PDOStatement::rowCount() retourne le nombre de lignes affectées par la dernière
        // requête DELETE, INSERT ou UPDATE exécutée par l'objet PDOStatement correspondant.
        // https://www.php.net/manual/fr/pdostatement.rowcount.php
        $deleteCount = $pdoStatement->rowCount();

        // Renvoie true ssi on a bien supprimé une ligne de la BDD
        return ($deleteCount > 0);
    }

    public function mettreAJour(AbstractDataObject $object): void
    {
        $nomTable = $this->getNomTable();
        $nomClePrimaire = $this->getNomClePrimaire();
        $nomsColonnes = $this->getNomsColonnes();

        $partiesSet = array_map(function ($nomcolonne) {
            return "$nomcolonne = :{$nomcolonne}_tag";
        }, $nomsColonnes);
        $setString = join(',', $partiesSet);
        $whereString = "$nomClePrimaire = :{$nomClePrimaire}_tag";

        $sql = "UPDATE $nomTable SET $setString WHERE $whereString";
        // Préparation de la requête
        $req_prep = ConnexionBaseDeDonnees::getPDO()->prepare($sql);

        $objetFormatTableau = $object->exporterEnFormatRequetePreparee();
        $req_prep->execute($objetFormatTableau);

        return;
    }

    public function ajouter(AbstractDataObject $object): bool
    {
        $nomTable = $this->getNomTable();
        $nomsColonnes = $this->getNomsColonnes();

        $insertString = '(' . join(', ', $nomsColonnes) . ')';

        $partiesValues = array_map(function ($nomcolonne) {
            return ":{$nomcolonne}_tag";
        }, $nomsColonnes);
        $valueString = '(' . join(', ', $partiesValues) . ')';

        $sql = "INSERT INTO $nomTable $insertString VALUES $valueString";
        // Préparation de la requête
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $objetFormatTableau = $object->exporterEnFormatRequetePreparee();

        try {
            $pdoStatement->execute($objetFormatTableau);
            return true;
        } catch (PDOException $exception) {
            if ($pdoStatement->errorCode() === "23000") {
                // Je ne traite que l'erreur "Duplicate entry"
                return false;
            } else {
                // Pour les autres erreurs, je transmets l'exception
                throw $exception;
            }
        }
    }
}
