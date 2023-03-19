<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\Utilisateur;
use Exception;

class UtilisateurRepository extends AbstractRepository
{
//    /**
//     * @return Utilisateur[]
//     */
//    public static function getUtilisateurs() : array {
//        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM utilisateur");
//
//        $utilisateurs = [];
//        foreach($pdoStatement as $utilisateurFormatTableau) {
//            $utilisateurs[] = UtilisateurRepository::construire($utilisateurFormatTableau);
//        }
//
//        return $utilisateurs;
//    }

    public function construireDepuisTableau(array $utilisateurTableau): Utilisateur
    {
        return new Utilisateur(
            $utilisateurTableau["login"],
            $utilisateurTableau["mdp_hache"],
            $utilisateurTableau["email"],
            $utilisateurTableau["nom"],
            $utilisateurTableau["prenom"],

            $utilisateurTableau["est_admin"]

        );
    }

    public function getNomTable(): string
    {

        return 'users';
    }

    protected function getNomClePrimaire(): string
    {
        return 'idUser';
    }

    protected function getNomsColonnes(): array
    {
        return ["idUser", "mdphache", "emailUser", "nom","prenom"];
    }
}