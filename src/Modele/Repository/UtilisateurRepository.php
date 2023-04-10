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

    public function ajouterUserAValider(Utilisateur $user): bool
    {
        //on verifie si un user n'est pas deja en cours de validation
        $sql = "SELECT login from usersexploreavalider WHERE login = :loginTag";

        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $user->getLogin(),
        );

        $pdoStatement->execute($values);
        $result = $pdoStatement->fetch();
        if($result != null)
        {
            return false;
        }

        $sql = "INSERT INTO usersexploreavalider VALUES (:loginTag,:nonceTag, :emailTag)";

        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $user->getLogin(),
            "nonceTag" => $this->genererNonce(),
            "emailTag" => $user->getEmail()
        );

        $pdoStatement->execute($values);
        return true;

    }

    function genererNonce():string {
        $characters = '0123456789';
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, 9)];
        }
        return $randomString;
    }

    protected function getNonce($user): ?int
    {
        $sql = "SELECT nonce FROM usersexploreavalider WHERE login = :loginTag";

        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $user->getLogin()
        );

        $pdoStatement->execute($values);

        $result = $pdoStatement->fetch();
        if($result != null)
        {
            return $result['NONCE'];
        }
        else return null;
    }
}