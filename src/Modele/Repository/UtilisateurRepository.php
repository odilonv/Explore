<?php

namespace Explore\Modele\Repository;


use Exception;
use Explore\Modele\DataObject\Utilisateur;

class UtilisateurRepository extends AbstractRepository implements UtilisateurRepositoryInterface
{

    public function __construct(ConnexionBaseDeDonneesInterface $connexionBaseDeDonnees)
    {
        parent::__construct($connexionBaseDeDonnees);
    }

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
            $utilisateurTableau["profilepicturename"]
        );
    }

    public function getNomTable(): string
    {
        return 'usersExplore';
    }

    protected function getNomClePrimaire(): string
    {
        return 'login';
    }

    protected function getNomsColonnes(): array
    {
        return ["login", "mdp_hache",  "email", "profilePictureName"];
    }
}