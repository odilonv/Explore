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
            $utilisateurTableau["nom"],
            $utilisateurTableau["prenom"],
            $utilisateurTableau["mdp_hache"],
            $utilisateurTableau["est_admin"],
            $utilisateurTableau["email"],
            $utilisateurTableau["email_a_valider"],
            $utilisateurTableau["nonce"],
        );
    }

    public function getNomTable(): string
    {
        throw new Exception("À vous de créer votre table utilisateur !");
        return 'utilisateur';
    }

    protected function getNomClePrimaire(): string
    {
        return 'login';
    }

    protected function getNomsColonnes(): array
    {
        return ["login", "nom", "prenom", "mdp_hache", "est_admin", "email", "email_a_valider", "nonce"];
    }
}